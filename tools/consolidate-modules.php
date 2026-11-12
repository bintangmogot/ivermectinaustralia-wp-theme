<?php
/**
 * Explicit, lossless transfer. No hooks run this on website loads.
 * wp eval-file tools/consolidate-modules.php [apply|retire]
 * Default previews. `apply` copies values and installs editable ACF layouts.
 * `retire` verifies the copies again, then moves the old field group to trash.
 */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit("Use WP-CLI eval-file.\n"); }
if (!function_exists('acf_import_field_group')) { throw new RuntimeException('ACF must be active.'); }
require_once __DIR__ . '/../inc/module-transfer-schema.php';

function iva_transfer_meta(int $id, string $parent): array {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND (meta_key IN (%s,%s) OR meta_key LIKE %s OR meta_key LIKE %s) ORDER BY meta_key, meta_value",
        $id, $parent, '_' . $parent, $wpdb->esc_like($parent . '_') . '%', $wpdb->esc_like('_' . $parent . '_') . '%'
    ), ARRAY_A);
}
function iva_transfer_sort(array $rows): array {
    usort($rows, static fn($a, $b) => strcmp($a['meta_key'] . "\0" . $a['meta_value'], $b['meta_key'] . "\0" . $b['meta_value']));
    return $rows;
}
function iva_transfer_schema_signature(array $schema): string {
    $normalize = static function ($value) use (&$normalize) {
        if (!is_array($value)) { return $value; }
        // Strip runtime attributes only from field/layout definitions. Keep
        // values inside choices, conditional logic and default-value payloads.
        if (isset($value['key'], $value['name']) && (isset($value['type']) || str_starts_with($value['key'], 'layout_'))) {
            foreach (['ID', 'parent', 'parent_layout', 'prefix', 'value', '_valid', '_name', '_input', '_prepare', '_clone', 'local', 'local_file'] as $key) { unset($value[$key]); }
        }
        foreach ($value as &$item) { $item = $normalize($item); }
        unset($item);
        if (!array_is_list($value)) { ksort($value); }
        return $value;
    };
    return hash('sha256', wp_json_encode($normalize($schema), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}
function iva_transfer_root(array $rows, string $parent): array {
    foreach ($rows as $row) {
        if ($row['meta_key'] === $parent) {
            $value = @unserialize($row['meta_value'], ['allowed_classes' => false]);
            return is_array($value) ? $value : [];
        }
    }
    return [];
}
function iva_transfer_expected(array $source, array $map): array {
    $result = [];
    foreach ($source as $row) {
        $key = $row['meta_key']; $value = $row['meta_value'];
        $target = str_starts_with($key, '_') ? '_' . substr_replace(substr($key, 1), 'page_modules', 0, 7) : substr_replace($key, 'page_modules', 0, 7);
        if ($key === 'modules') {
            $layouts = iva_transfer_root([$row], 'modules');
            foreach ($layouts as &$layout) {
                if (!isset($map['layouts'][$layout])) { throw new RuntimeException('Unmapped legacy layout; refresh the backup/mapping first.'); }
                $layout = $map['layouts'][$layout];
            }
            unset($layout);
            $value = serialize($layouts);
        } elseif (str_starts_with($key, '_') && str_starts_with($value, 'field_')) {
            if (isset($map['fields'][$value])) {
                $value = $map['fields'][$value];
            } elseif (acf_get_field($value)) {
                throw new RuntimeException('A currently defined legacy field is unmapped; refresh the backup/mapping first.');
            }
            // Deleted historical field references and ACF layout-state metadata
            // are copied verbatim. They already had no active editor definition;
            // keeping them preserves revision/history data without inventing fields.
        }
        $result[] = ['meta_key' => $target, 'meta_value' => $value];
    }
    if (!in_array('_page_modules', array_column($result, 'meta_key'), true)) {
        $result[] = ['meta_key' => '_page_modules', 'meta_value' => $map['target_field']];
    }
    return iva_transfer_sort($result);
}
/** Append previously inactive legacy rows as disabled, editable Page Modules. */
function iva_transfer_append_inactive(array $source, array $target, array $map): array {
    $flexible_type = acf_get_field_type('flexible_content');
    if (!$flexible_type || !method_exists($flexible_type, 'get_disabled_layouts') || !method_exists($flexible_type, 'should_disable_layout')) {
        throw new RuntimeException('This ACF version cannot safely preserve inactive layouts.');
    }
    $old = iva_transfer_expected($source, $map);
    $modern_layouts = iva_transfer_root($target, 'page_modules');
    $old_layouts = iva_transfer_root($old, 'page_modules');
    if (!$modern_layouts || !$old_layouts) { throw new RuntimeException('Cannot append an empty layout set.'); }
    $offset = count($modern_layouts); $result = []; $seen = []; $layout_meta = ['disabled'=>[], 'renamed'=>[]]; $old_meta = [];
    foreach ($target as $row) {
        $key=$row['meta_key'];
        if (isset($seen[$key])) { throw new RuntimeException('Duplicate Page Modules metadata needs manual review.'); }
        $seen[$key]=true;
        if ($key==='page_modules') { continue; }
        if ($key==='_page_modules_layout_meta') {
            $value=maybe_unserialize($row['meta_value']);
            if (!is_array($value)) { throw new RuntimeException('Unexpected Page Modules layout state.'); }
            $layout_meta=$value + $layout_meta; continue;
        }
        $result[]=$row;
    }
    if (!is_array($layout_meta['disabled']) || !is_array($layout_meta['renamed'])) { throw new RuntimeException('Unexpected Page Modules layout state.'); }
    foreach ($old as $row) {
        $key=$row['meta_key'];
        if ($key==='page_modules' || $key==='_page_modules') { continue; }
        if ($key==='_page_modules_layout_meta') { $old_meta=maybe_unserialize($row['meta_value']); if(!is_array($old_meta))throw new RuntimeException('Unexpected legacy layout state.'); continue; }
        if (!preg_match('/^(_?)page_modules_(\d+)_(.*)$/', $key, $m)) { throw new RuntimeException('Unexpected legacy module metadata needs review.'); }
        $key=$m[1].'page_modules_'.((int)$m[2]+$offset).'_'.$m[3];
        if (isset($seen[$key])) { throw new RuntimeException('Inactive module would overwrite an existing field.'); }
        $seen[$key]=true;$result[]=['meta_key'=>$key,'meta_value'=>$row['meta_value']];
    }
    for($i=0;$i<count($old_layouts);$i++){$layout_meta['disabled'][]=$offset+$i;}
    $layout_meta['disabled']=array_values(array_unique($layout_meta['disabled']));
    foreach ((array)($old_meta['renamed']??[]) as $index=>$label) {
        if (!ctype_digit((string)$index) || (int)$index >= count($old_layouts)) { throw new RuntimeException('Unexpected legacy layout label index.'); }
        $layout_meta['renamed'][(int)$index+$offset]=$label;
    }
    if (!isset($seen['_page_modules'])) { $result[]=['meta_key'=>'_page_modules','meta_value'=>$map['target_field']]; }
    $result[]=['meta_key'=>'page_modules','meta_value'=>serialize(array_merge($modern_layouts,$old_layouts))];
    $result[]=['meta_key'=>'_page_modules_layout_meta','meta_value'=>serialize($layout_meta)];
    return iva_transfer_sort($result);
}
function iva_transfer_db_group(string $key): ?array {
    $local = acf_is_local_enabled(); acf_disable_local();
    acf_get_store('fields')->reset(); acf_get_store('field-groups')->reset();
    try {
        $raw = acf_get_raw_field_group($key);
        if (!$raw) { return null; }
        $raw['fields'] = acf_get_fields($raw);
        return acf_prepare_field_group_for_export($raw);
    } finally {
        if ($local) { acf_enable_local(); }
        acf_get_store('fields')->reset(); acf_get_store('field-groups')->reset();
    }
}
function iva_transfer_import_schema(array $group): array {
    // ACF stores serialized field settings in post_content. Editorial filters
    // (KSES, automatic tag balancing, block parsing) must not alter that payload.
    global $wp_filter, $wpdb;
    $existing = $wpdb->get_col($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type='acf-field-group' AND post_status<>'trash' AND post_name=%s", $group['key']));
    if (count($existing) > 1) { throw new RuntimeException('Duplicate target field groups already exist; review them before importing.'); }
    // Native ACF import requires an ID to update an existing group. Omitting it
    // creates a second group with the same key instead of updating the first.
    $group['ID'] = $existing ? (int) $existing[0] : 0;
    $restore = [];
    foreach (['content_save_pre', 'excerpt_save_pre'] as $hook) {
        $restore[$hook] = isset($wp_filter[$hook]) ? clone $wp_filter[$hook] : null;
        remove_all_filters($hook);
    }
    try { return acf_import_field_group($group); }
    finally {
        foreach ($restore as $hook => $callbacks) {
            if ($callbacks !== null) { $wp_filter[$hook] = $callbacks; }
            else { unset($wp_filter[$hook]); }
        }
    }
}
function iva_transfer_validate_schema(array $definition): void {
    global $wpdb;
    if (isset($definition['key'], $definition['type'], $definition['name'])) {
        $stored = $wpdb->get_row($wpdb->prepare("SELECT post_content,post_excerpt FROM {$wpdb->posts} WHERE post_type='acf-field' AND post_status='publish' AND post_name=%s LIMIT 1", $definition['key']), ARRAY_A);
        $settings = $stored ? @unserialize($stored['post_content'], ['allowed_classes' => false]) : false;
        if (!is_array($settings) || ($settings['type'] ?? '') !== $definition['type'] || $stored['post_excerpt'] !== $definition['name']) {
            throw new RuntimeException('Editable field definition failed database readback: ' . $definition['key']);
        }
    }
    foreach ($definition as $value) {
        if (is_array($value)) { iva_transfer_validate_schema($value); }
    }
}

global $wpdb;
$mode = $args[0] ?? 'preview';
if (!in_array($mode, ['preview', 'apply', 'retire'], true)) { throw new RuntimeException('Use preview, apply, or retire.'); }
$map = json_decode(file_get_contents(get_theme_file_path('/config/module-transfer-map.json')), true, 512, JSON_THROW_ON_ERROR);
$schema_path = get_theme_file_path('/acf-json/group_page_modules.json');
$schema = json_decode(file_get_contents($schema_path), true, 512, JSON_THROW_ON_ERROR);
$old_group = $wpdb->get_row($wpdb->prepare("SELECT ID,post_status FROM {$wpdb->posts} WHERE post_type='acf-field-group' AND post_name=%s LIMIT 1", $map['source_group']), ARRAY_A);
$target_group_count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='acf-field-group' AND post_status<>'trash' AND post_name=%s", $map['target_group']));
if ($target_group_count > 1) { throw new RuntimeException('Duplicate Page Modules database definitions exist; review them before transferring or retiring a group.'); }
if ($old_group && $old_group['post_status'] !== 'trash') {
    $source_schema = acf_get_field($map['source_field']);
    if (!$source_schema || empty($map['source_schema_signature']) || iva_transfer_schema_signature($source_schema) !== $map['source_schema_signature']) {
        throw new RuntimeException('Legacy field definitions differ from the reviewed backup. Refresh the mapping before transferring content.');
    }
}
// Do not retire a group which is still registered by PHP/JSON elsewhere.
if ($mode === 'retire' && acf_get_local_field_group($map['source_group'])) {
    throw new RuntimeException('The old group is also registered in code/JSON. Review it before retirement.');
}
foreach ([$wpdb->termmeta, $wpdb->usermeta] as $table) {
    $value = $wpdb->get_col("SELECT meta_value FROM `$table` WHERE meta_key='modules'");
    foreach ($value as $root) {
        if (is_array(@unserialize($root, ['allowed_classes' => false])) && @unserialize($root, ['allowed_classes' => false])) {
            throw new RuntimeException('Legacy modules also exist outside posts; separate mapping is required.');
        }
    }
}
$option_modules = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name='options_modules'");
if ($option_modules && @unserialize($option_modules, ['allowed_classes' => false])) { throw new RuntimeException('Options modules need separate mapping.'); }
$ids = $wpdb->get_col("SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_key='modules' ORDER BY post_id");
$plan = []; $verified = []; $preserved = []; $conflicts = []; $source_hashes = []; $archived = [];
foreach ($ids as $id) {
    $id = (int) $id;
    $source = iva_transfer_meta($id, 'modules');
    if (!iva_transfer_root($source, 'modules')) { continue; }
    $source_hashes[$id] = hash('sha256', serialize($source));
    $target = iva_transfer_meta($id, 'page_modules');
    $target_layouts = iva_transfer_root($target, 'page_modules');
    if ($target_layouts && !array_filter($target_layouts, static fn($l) => str_starts_with((string) $l, $map['prefix']))) {
        // These old rows were inactive before transfer. Keep them editable as
        // disabled rows rather than making them visible or losing editor access.
        if (metadata_exists('post', $id, '_iva_inactive_modules_manifest')) { $conflicts[$id] = 'Existing inactive-module manifest needs review.'; continue; }
        try { $expected = iva_transfer_append_inactive($source, $target, $map); }
        catch (RuntimeException $e) { $conflicts[$id] = $e->getMessage(); continue; }
        $plan[$id] = ['before'=>$target,'after'=>$expected,'append_inactive'=>true];
        $preserved[] = $id; continue;
    }
    try { $expected = iva_transfer_expected($source, $map); }
    catch (RuntimeException $e) { $conflicts[$id] = $e->getMessage(); continue; }
    if ($target_layouts) {
        $manifest = get_post_meta($id, '_iva_inactive_modules_manifest', true);
        if (is_array($manifest) && ($manifest['source_hash'] ?? '') === $source_hashes[$id]
            && ($manifest['target_hash'] ?? '') === hash('sha256', serialize(iva_transfer_sort($target)))) { $verified[] = $id; $preserved[] = $id; }
        elseif (iva_transfer_sort($target) === $expected) { $verified[] = $id; }
        else { $conflicts[$id] = 'Existing transferred or mixed content differs. Preserve and review it.'; }
        continue;
    }
    if ($target) {
        if ($wpdb->get_var($wpdb->prepare("SELECT meta_id FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key='_iva_page_modules_before_transfer'", $id))) {
            $conflicts[$id] = 'An earlier recovery archive exists; review instead of replacing it.'; continue;
        }
        $archived[] = $id;
    }
    if (!$wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE ID=%d", $id))) {
        $conflicts[$id] = 'Source metadata has no post; preserve for manual review.'; continue;
    }
    $plan[$id] = ['before' => $target, 'after' => $expected];
}
echo wp_json_encode(['mode' => $mode, 'source' => home_url('/'), 'transfer_records' => count($plan), 'already_verified' => count($verified), 'existing_modern_records_preserved' => count($preserved), 'empty_target_metadata_to_archive' => $archived, 'conflicts' => $conflicts], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
if ($mode === 'preview') { return; }
if (!current_user_can('manage_options')) { throw new RuntimeException('Run the mutating command with WP-CLI --user=<administrator login or ID>.'); }
if ($mode === 'apply' && $plan && !$old_group) { throw new RuntimeException('The source field group is missing; restore its reviewed definition before transferring values.'); }
if ($conflicts) { throw new RuntimeException('Preflight conflicts: nothing was written.'); }
if ($mode === 'retire' && $plan) { throw new RuntimeException('Run apply and verify all transfers before retiring the old group.'); }
if ($mode === 'retire' && (!$old_group || $old_group['post_status'] === 'trash')) { echo "Old field group is already retired.\n"; return; }
if ($mode === 'retire' && (!defined('EMPTY_TRASH_DAYS') || EMPTY_TRASH_DAYS <= 0)) {
    throw new RuntimeException('WordPress trash is disabled; refusing permanent schema deletion.');
}
$transactional = true;
foreach ([$wpdb->posts, $wpdb->postmeta, $wpdb->options] as $table) {
    $engine = $wpdb->get_var($wpdb->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=%s', $table));
    if (strtolower((string) $engine) !== 'innodb') { $transactional = false; }
}
$existing_schema = iva_transfer_db_group($map['target_group']);
$merged = $existing_schema ?: $schema;
if ($existing_schema) {
    $found = false;
    foreach ($merged['fields'] as &$field) {
        if ($field['key'] !== $map['target_field']) { continue; }
        $found = true;
        if ($field['name'] !== 'page_modules' || $field['type'] !== 'flexible_content') { throw new RuntimeException('Unexpected existing target field.'); }
        $layouts_to_merge = $schema['fields'][0]['layouts'];
        $field['layouts'] = iva_transfer_merge_layout_definitions((array) ($field['layouts'] ?? []), $layouts_to_merge);
    }
    unset($field);
    if (!$found) { throw new RuntimeException('Existing Page Modules group has a different parent field.'); }
}
$backup = tempnam(sys_get_temp_dir(), 'ivermectin-module-transfer-');
if (!$backup) { throw new RuntimeException('Cannot create a recovery snapshot.'); }
chmod($backup, 0600);
if (file_put_contents($backup, wp_json_encode(['site' => home_url('/'), 'mode' => $mode, 'original_target_group' => $existing_schema, 'original_legacy_group' => iva_transfer_db_group($map['source_group']), 'records' => $plan], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) { throw new RuntimeException('Cannot save recovery snapshot.'); }
$original_json = file_get_contents($schema_path);
$lock = 'iva_module_transfer_' . md5($wpdb->prefix . DB_NAME);
if ((int) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,0)', $lock)) !== 1) { throw new RuntimeException('Another module transfer is running.'); }
if ($transactional) { $wpdb->query('START TRANSACTION'); }
$written = []; $append_archive_ids = []; $retired = false;
add_filter('acf/settings/save_json', '__return_false', 999);
try {
    if ($mode === 'apply') {
        $imported = iva_transfer_import_schema($merged);
        if (empty($imported['ID'])) { throw new RuntimeException('Field definition import failed.'); }
        iva_transfer_validate_schema($merged);
        foreach ($map['fields'] as $field_key) {
            if (!$wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type='acf-field' AND post_name=%s AND post_status='publish' LIMIT 1", $field_key))) {
                throw new RuntimeException('An imported field is missing from the editable database definition.');
            }
        }
        foreach ($plan as $id => $record) {
            if (hash('sha256', serialize(iva_transfer_meta($id, 'modules'))) !== $source_hashes[$id] || iva_transfer_meta($id, 'page_modules') !== $record['before']) {
                throw new RuntimeException('Content changed since preflight; retry after reviewing the latest values.');
            }
            $written[] = $id;
            if (!empty($record['append_inactive'])) {
                if($wpdb->insert($wpdb->postmeta,['post_id'=>$id,'meta_key'=>'_iva_page_modules_before_transfer','meta_value'=>serialize($record['before'])],['%d','%s','%s'])===false)throw new RuntimeException('Cannot archive existing Page Modules metadata.');
                $append_archive_ids[$id]=(int)$wpdb->insert_id;
                $before_by_key=[];foreach($record['before'] as $row){$before_by_key[$row['meta_key']]=$row['meta_value'];}
                foreach($record['after'] as $row){
                    $key=$row['meta_key'];if(in_array($key,['page_modules','_page_modules_layout_meta'],true))continue;
                    if(array_key_exists($key,$before_by_key)){if($before_by_key[$key]!==$row['meta_value'])throw new RuntimeException('Existing field would change.');continue;}
                    if($wpdb->insert($wpdb->postmeta,['post_id'=>$id]+$row,['%d','%s','%s'])===false)throw new RuntimeException('Cannot copy inactive module field.');
                }
                $layout_rows=array_values(array_filter($record['after'],static fn($r)=>$r['meta_key']==='_page_modules_layout_meta'));
                $layout_value=$layout_rows[0]['meta_value'];
                if(array_key_exists('_page_modules_layout_meta',$before_by_key)){
                    $updated=$wpdb->update($wpdb->postmeta,['meta_value'=>$layout_value],['post_id'=>$id,'meta_key'=>'_page_modules_layout_meta','meta_value'=>$before_by_key['_page_modules_layout_meta']],['%s'],['%d','%s','%s']);
                    if($updated!==1)throw new RuntimeException('Layout state changed during transfer.');
                }elseif($wpdb->insert($wpdb->postmeta,['post_id'=>$id,'meta_key'=>'_page_modules_layout_meta','meta_value'=>$layout_value],['%d','%s','%s'])===false)throw new RuntimeException('Cannot save layout state.');
                $partial=iva_transfer_meta($id,'page_modules');
                $expected_partial=array_values(array_filter($record['after'],static fn($r)=>$r['meta_key']!=='page_modules'));
                $expected_partial[]=['meta_key'=>'page_modules','meta_value'=>$before_by_key['page_modules']];
                if(iva_transfer_sort($partial)!==iva_transfer_sort($expected_partial))throw new RuntimeException('Inactive field readback failed before activation.');
                $root=array_values(array_filter($record['after'],static fn($r)=>$r['meta_key']==='page_modules'))[0]['meta_value'];
                $updated=$wpdb->update($wpdb->postmeta,['meta_value'=>$root],['post_id'=>$id,'meta_key'=>'page_modules','meta_value'=>$before_by_key['page_modules']],['%s'],['%d','%s','%s']);
                if($updated!==1)throw new RuntimeException('Page Modules changed before activation.');
                wp_cache_delete($id,'post_meta');
                if(iva_transfer_sort(iva_transfer_meta($id,'page_modules'))!==$record['after'])throw new RuntimeException('Inactive module readback failed.');
                $manifest=['source_hash'=>$source_hashes[$id],'target_hash'=>hash('sha256',serialize($record['after']))];
                if($wpdb->insert($wpdb->postmeta,['post_id'=>$id,'meta_key'=>'_iva_inactive_modules_manifest','meta_value'=>serialize($manifest)],['%d','%s','%s'])===false)throw new RuntimeException('Cannot save transfer manifest.');
                wp_cache_delete($id,'post_meta');
                continue;
            }
            if ($record['before']) {
                if ($wpdb->insert($wpdb->postmeta, ['post_id' => $id, 'meta_key' => '_iva_page_modules_before_transfer', 'meta_value' => serialize($record['before'])], ['%d', '%s', '%s']) === false) { throw new RuntimeException('Cannot archive the empty target metadata.'); }
            }
            foreach ($record['before'] as $before) {
                if ($wpdb->delete($wpdb->postmeta, ['post_id' => $id, 'meta_key' => $before['meta_key']]) === false) { throw new RuntimeException('Cannot prepare the archived target.'); }
            }
            // Direct metadata inserts also preserve revisions. update_post_meta()
            // would redirect revision writes to their parent and corrupt it.
            $copy = array_values(array_filter($record['after'], static fn($r) => $r['meta_key'] !== 'page_modules'));
            $root = array_values(array_filter($record['after'], static fn($r) => $r['meta_key'] === 'page_modules'));
            foreach ($copy as $row) {
                if ($wpdb->insert($wpdb->postmeta, ['post_id' => $id] + $row, ['%d', '%s', '%s']) === false) { throw new RuntimeException('Metadata copy failed.'); }
            }
            if (iva_transfer_sort(iva_transfer_meta($id, 'page_modules')) !== iva_transfer_sort($copy)) { throw new RuntimeException('Subfield readback failed before activation.'); }
            // Activate only after every subfield is present, including on MyISAM.
            foreach ($root as $row) {
                if ($wpdb->insert($wpdb->postmeta, ['post_id' => $id] + $row, ['%d', '%s', '%s']) === false) { throw new RuntimeException('Root activation failed.'); }
            }
            wp_cache_delete($id, 'post_meta');
            if (iva_transfer_sort(iva_transfer_meta($id, 'page_modules')) !== $record['after']) { throw new RuntimeException('Copied metadata failed exact readback.'); }
        }
        // Keep native Local JSON consistent with the editable database schema.
        remove_filter('acf/settings/save_json', '__return_false', 999);
        if (!acf_write_json_field_group($merged)) { throw new RuntimeException('Cannot export the merged editable field definition.'); }
    } elseif ($mode === 'retire') {
        if (!wp_trash_post((int) $old_group['ID'])) { throw new RuntimeException('Could not move the old field group to trash.'); }
        $retired = true;
    }
    foreach ($source_hashes as $id => $hash) {
        if (hash('sha256', serialize(iva_transfer_meta($id, 'modules'))) !== $hash) { throw new RuntimeException('Original module metadata changed unexpectedly.'); }
    }
    if ($transactional && $wpdb->query('COMMIT') === false) { throw new RuntimeException('Commit failed.'); }
} catch (Throwable $error) {
    if ($transactional) { $wpdb->query('ROLLBACK'); }
    else {
        // MyISAM needs compensating restoration. Restore only values still
        // belonging to this transfer; never overwrite a concurrent editor save.
        foreach (array_reverse($written) as $id) {
            $actual = iva_transfer_meta($id, 'page_modules');
            $allowed = array_merge($plan[$id]['before'], $plan[$id]['after']);
            if (array_filter($actual, static fn($r) => !in_array($r, $allowed, true))) {
                echo "Concurrent target edits on record {$id}; preserved for manual recovery using {$backup}.\n";
                continue;
            }
            if (!empty($plan[$id]['append_inactive'])) {
                $before=[];$after=[];$now=[];
                foreach($plan[$id]['before'] as $row){$before[$row['meta_key']]=$row['meta_value'];}
                foreach($plan[$id]['after'] as $row){$after[$row['meta_key']]=$row['meta_value'];}
                foreach($actual as $row){$now[$row['meta_key']]=$row['meta_value'];}
                $safe=true;foreach($before as $key=>$value){if(!array_key_exists($key,$now)||($now[$key]!==$value&&$now[$key]!==($after[$key]??null)))$safe=false;}
                if(!$safe){echo "Concurrent target edits on record {$id}; preserved for manual recovery using {$backup}.\n";continue;}
                foreach($after as $key=>$value){
                    if(!array_key_exists($key,$before)){
                        if(array_key_exists($key,$now))$wpdb->delete($wpdb->postmeta,['post_id'=>$id,'meta_key'=>$key,'meta_value'=>$value],['%d','%s','%s']);
                    }elseif($before[$key]!==$value && ($now[$key]??null)===$value){
                        $wpdb->update($wpdb->postmeta,['meta_value'=>$before[$key]],['post_id'=>$id,'meta_key'=>$key,'meta_value'=>$value],['%s'],['%d','%s','%s']);
                    }
                }
                $wpdb->delete($wpdb->postmeta,['post_id'=>$id,'meta_key'=>'_iva_inactive_modules_manifest']);
                if(!empty($append_archive_ids[$id]))$wpdb->delete($wpdb->postmeta,['meta_id'=>$append_archive_ids[$id]],['%d']);
                wp_cache_delete($id,'post_meta');
                continue;
            }
            foreach (array_unique(array_column($actual, 'meta_key')) as $key) { $wpdb->delete($wpdb->postmeta, ['post_id' => $id, 'meta_key' => $key]); }
            foreach ($plan[$id]['before'] as $row) { $wpdb->insert($wpdb->postmeta, ['post_id' => $id] + $row, ['%d', '%s', '%s']); }
            $wpdb->delete($wpdb->postmeta, ['post_id' => $id, 'meta_key' => '_iva_page_modules_before_transfer']);
            wp_cache_delete($id, 'post_meta');
        }
        if ($retired) { wp_untrash_post((int) $old_group['ID']); }
        if ($existing_schema) { iva_transfer_import_schema($existing_schema); }
    }
    file_put_contents($schema_path, $original_json);
    throw $error;
} finally {
    remove_filter('acf/settings/save_json', '__return_false', 999);
    $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock));
}
acf_get_store('fields')->reset(); acf_get_store('field-groups')->reset(); acf_get_store('values')->reset();
// ACF also caches flexible-layout disabled state on the field type instance.
$flexible_type = acf_get_field_type('flexible_content');
if ($flexible_type && property_exists($flexible_type, 'layout_meta')) { $flexible_type->layout_meta = []; }
echo ($mode === 'apply' ? 'Transferred and verified every copied metadata value. Original Modules metadata retained.' : 'Verified all transfers and moved the old Modules field group to trash.') . "\n";
echo 'Private recovery snapshot: ' . $backup . "\n";
