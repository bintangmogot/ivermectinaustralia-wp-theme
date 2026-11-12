<?php
/** Explicit product content transfer. Run with WP-CLI eval-file, preview by default. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit("Use WP-CLI eval-file.\n"); }
if (!function_exists('get_field') || !function_exists('wc_get_product')) { throw new RuntimeException('ACF and WooCommerce must be active.'); }
$mode = $args[0] ?? 'preview';
if (!in_array($mode, ['preview', 'apply'], true)) { throw new RuntimeException('Use preview or apply.'); }
global $wpdb;
$fields = ['product_summary' => 'field_wa_product_summary_content', 'product_description' => 'field_wa_product_description_content', 'product_tabs' => 'field_iva_product_tabs_items', 'product_faqs' => 'field_dsg_624949de81'];
foreach ($fields as $key) { if (!acf_get_field($key)) { throw new RuntimeException('A Page Modules field is missing: ' . $key); } }
if (!acf_get_field('field_iva_summary_preserve_raw')) { throw new RuntimeException('The summary preservation field is missing.'); }
if (!acf_get_field('field_iva_product_faq_legacy_position')) { throw new RuntimeException('The FAQ position preservation field is missing.'); }
// Local JSON alone is not enough: the transferred fields must exist in the
// editable ACF database group before product data can be activated.
$local_enabled=acf_is_local_enabled();acf_disable_local();acf_get_store('fields')->reset();acf_get_store('field-groups')->reset();
try {
    $group=acf_get_raw_field_group('group_page_modules');
    $group_fields=$group ? acf_get_fields($group) : [];
    $root=null;foreach((array)$group_fields as $candidate){if(($candidate['key']??'')==='field_iva_page_modules'){$root=$candidate;break;}}
    if(!$root || ($root['type']??'')!=='flexible_content')throw new RuntimeException('The editable Page Modules group is missing. Run the module transfer first.');
    $layout_fields=[];foreach((array)($root['layouts']??[]) as $layout){$keys=[];$walk=static function($items) use (&$walk,&$keys){foreach((array)$items as $item){if(!empty($item['key']))$keys[]=$item['key'];if(!empty($item['sub_fields']))$walk($item['sub_fields']);}};$walk($layout['sub_fields']??[]);$layout_fields[$layout['name']]=$keys;}
    foreach($fields as $layout=>$key){if(!in_array($key,$layout_fields[$layout]??[],true))throw new RuntimeException('Editable Page Modules layout is missing: '.$layout);}
    if(!in_array('field_iva_summary_preserve_raw',$layout_fields['product_summary']??[],true)||!in_array('field_iva_product_faq_legacy_position',$layout_fields['product_faqs']??[],true))throw new RuntimeException('Editable preservation fields are missing. Run the module transfer first.');
} finally {
    if($local_enabled)acf_enable_local();acf_get_store('fields')->reset();acf_get_store('field-groups')->reset();
}
function iva_product_transfer_sources($post, $product): array {
    $id = (int) $post->ID;
    $short = get_field('short_description', $id) ?: get_post_meta($id, 'short_desc', true);
    if (!$short) {
        $raw = $product->get_short_description();
        preg_match('/<!-- en -->(.+?)<!-- \/en -->/s', $raw, $match);
        $short = !empty($match[1]) ? trim($match[1]) : strip_tags($raw, '<p><a><strong><b><i><em><ul><ol><li><br>');
    }
    $tabs = get_field('extra_tabs', $id); $faqs = get_field('faqs', $id);
    return ['product_summary' => (string) $short, 'product_description' => (string) (get_post_meta($id, 'main_desc', true) ?: $post->post_content),
        'product_tabs' => is_array($tabs) ? $tabs : [], 'product_faqs' => is_array($faqs) ? $faqs : [], 'faq_heading' => (string) get_field('faq_title', $id)];
}
$ids = get_posts(['post_type' => 'product', 'post_status' => ['publish','draft','private','pending','future'], 'posts_per_page' => -1, 'fields' => 'ids']);
$plan = []; $conflicts = []; $preserved = [];
foreach ($ids as $id) {
    $id = (int) $id; $post = get_post($id); $product = wc_get_product($id);
    if (!$post || !$product) { $conflicts[$id] = 'Product unavailable'; continue; }
    $meta = $wpdb->get_results($wpdb->prepare("SELECT meta_key,meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND (meta_key IN ('page_modules','_page_modules') OR meta_key LIKE %s OR meta_key LIKE %s) ORDER BY meta_key,meta_value", $id, $wpdb->esc_like('page_modules_') . '%', $wpdb->esc_like('_page_modules_') . '%'), ARRAY_A);
    foreach (['page_modules','_page_modules'] as $key) { if (count(array_filter($meta, static fn($r) => $r['meta_key'] === $key)) > 1) { $conflicts[$id] = 'Duplicate Page Modules metadata'; continue 2; } }
    foreach ($meta as $row) { if ($row['meta_key'] === '_page_modules' && $row['meta_value'] !== 'field_iva_page_modules') { $conflicts[$id] = 'Unexpected Page Modules field reference'; continue 2; } }
    $root = get_post_meta($id, 'page_modules', true);
    if ($root !== '' && !is_array($root)) { $conflicts[$id] = 'Unexpected Page Modules value'; continue; }
    $layouts = is_array($root) ? array_values($root) : [];
    if (count($layouts) !== count(array_filter($layouts, 'is_string'))) { $conflicts[$id] = 'Unexpected layout list'; continue; }
    $source_data = iva_product_transfer_sources($post, $product);
    $sources = $source_data; unset($sources['faq_heading']);
    $new = [];
    foreach ($sources as $layout => $value) {
        if (in_array($layout, $layouts, true)) { $preserved[$layout] = ($preserved[$layout] ?? 0) + 1; continue; }
        if ($value === '' || $value === []) { continue; }
        $index = count($layouts); $prefix = 'page_modules_' . $index . '_';
        foreach ($meta as $row) {
            if (str_starts_with(ltrim($row['meta_key'], '_'), $prefix)) { $conflicts[$id] = 'Target row contains existing metadata'; continue 3; }
        }
        $layouts[] = $layout;
        if ($layout === 'product_summary' || $layout === 'product_description') {
            $new[$prefix . 'content'] = $value; $new['_' . $prefix . 'content'] = $fields[$layout];
            if ($layout === 'product_summary') { $new[$prefix . 'preserve_raw'] = '1'; $new['_' . $prefix . 'preserve_raw'] = 'field_iva_summary_preserve_raw'; }
        } else {
            $name = $layout === 'product_tabs' ? 'tabs' : 'faq_items';
            $new[$prefix . $name] = count($value); $new['_' . $prefix . $name] = $fields[$layout];
            if ($layout === 'product_faqs') {
                $new[$prefix . 'heading'] = $source_data['faq_heading']; $new['_' . $prefix . 'heading'] = 'field_dsg_title_en_123';
                $new[$prefix . 'legacy_position'] = '1'; $new['_' . $prefix . 'legacy_position'] = 'field_iva_product_faq_legacy_position';
            }
            foreach (array_values($value) as $i => $item) {
                $sub = $layout === 'product_tabs' ? ['tab_title'=>'field_iva_product_tabs_title','tab_content'=>'field_iva_product_tabs_content'] : ['question'=>'field_dsg_ae5395b1a7','answer'=>'field_dsg_4ececabc41'];
                foreach ($sub as $name2 => $key) {
                    if (!array_key_exists($name2, $item)) { $conflicts[$id] = 'A legacy row has an unexpected field'; continue 4; }
                    $k = $prefix . $name . '_' . $i . '_' . $name2; $new[$k] = $item[$name2]; $new['_' . $k] = $key;
                }
            }
        }
    }
    if ($new && array_filter($layouts, static fn($layout) => str_starts_with($layout, 'previous__'))) {
        $conflicts[$id] = 'Product has transferred legacy sections; review mixed layout placement before copying.';
        continue;
    }
    if ($new) { $plan[$id] = ['before' => $meta, 'layouts' => $layouts, 'new' => $new, 'source_hash' => hash('sha256', serialize([$source_data,$post->post_content,$post->post_excerpt]))]; }
}
echo wp_json_encode(['mode'=>$mode,'products_examined'=>count($ids),'products_to_transfer'=>count($plan),'existing_modules_preserved'=>$preserved,'conflicts'=>$conflicts], JSON_PRETTY_PRINT) . "\n";
if ($mode === 'preview') { return; }
if (!current_user_can('manage_options')) { throw new RuntimeException('Run with an administrator.'); }
if ($conflicts) { throw new RuntimeException('Conflicts found; nothing was written.'); }
$transactional=true;
foreach ([$wpdb->posts,$wpdb->postmeta] as $table) {
    $engine=$wpdb->get_var($wpdb->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=%s',$table));
    if (strtolower((string)$engine)!=='innodb') { $transactional=false; }
}
$backup=tempnam(sys_get_temp_dir(),'iva-product-transfer-');if(!$backup)throw new RuntimeException('Cannot create recovery snapshot');chmod($backup,0600);
if(file_put_contents($backup,wp_json_encode($plan))===false)throw new RuntimeException('Cannot write recovery snapshot');
$lock='iva_product_transfer_'.md5($wpdb->prefix.DB_NAME);if((int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,0)',$lock))!==1)throw new RuntimeException('Another transfer is running');
try {
    if($transactional && $wpdb->query('START TRANSACTION')===false)throw new RuntimeException('Cannot start transaction');
    $applied=[];
    foreach($plan as $id=>$entry){
        $actual=$wpdb->get_results($wpdb->prepare("SELECT meta_key,meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND (meta_key IN ('page_modules','_page_modules') OR meta_key LIKE %s OR meta_key LIKE %s) ORDER BY meta_key,meta_value",$id,$wpdb->esc_like('page_modules_').'%',$wpdb->esc_like('_page_modules_').'%'),ARRAY_A);
        if($actual!==$entry['before'])throw new RuntimeException('Target changed during review: '.$id);
        clean_post_cache($id); wp_cache_delete($id,'post_meta'); acf_get_store('values')->reset();
        $post=get_post($id);$product=wc_get_product($id);
        if(!$post||!$product||hash('sha256',serialize([iva_product_transfer_sources($post,$product),$post->post_content,$post->post_excerpt]))!==$entry['source_hash'])throw new RuntimeException('Source changed during review: '.$id);
        $applied[]=$id;
        foreach($entry['new'] as $key=>$value){if((int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key=%s",$id,$key))>0)throw new RuntimeException('Target metadata exists: '.$id);if($wpdb->insert($wpdb->postmeta,['post_id'=>$id,'meta_key'=>$key,'meta_value'=>maybe_serialize($value)],['%d','%s','%s'])===false)throw new RuntimeException('Unable to copy metadata: '.$id);}
        wp_cache_delete($id,'post_meta');
        if(!metadata_exists('post',$id,'_page_modules')){if(!add_post_meta($id,'_page_modules','field_iva_page_modules',true))throw new RuntimeException('Cannot set parent reference');}
        $before_root=null;foreach($entry['before'] as $row){if($row['meta_key']==='page_modules')$before_root=maybe_unserialize($row['meta_value']);}
        wp_cache_delete($id,'post_meta');
        if(get_post_meta($id,'page_modules',true)!==($before_root ?? ''))throw new RuntimeException('Target changed before activation: '.$id);
        clean_post_cache($id);wp_cache_delete($id,'post_meta');acf_get_store('values')->reset();$post=get_post($id);$product=wc_get_product($id);
        if(!$post||!$product||hash('sha256',serialize([iva_product_transfer_sources($post,$product),$post->post_content,$post->post_excerpt]))!==$entry['source_hash'])throw new RuntimeException('Source changed before activation: '.$id);
        update_post_meta($id,'page_modules',$entry['layouts']);wp_cache_delete($id,'post_meta');
        if(get_post_meta($id,'page_modules',true)!==$entry['layouts'])throw new RuntimeException('Cannot verify layouts: '.$id);
        foreach($entry['new'] as $key=>$value){if((string)get_post_meta($id,$key,true)!==(string)$value)throw new RuntimeException('Cannot verify field: '.$id);}
    }
    if($transactional && $wpdb->query('COMMIT')===false)throw new RuntimeException('Cannot commit transaction');
}catch(Throwable $e){
    if($transactional){$wpdb->query('ROLLBACK');}
    else foreach(array_reverse($applied ?? []) as $id){
        $entry=$plan[$id];wp_cache_delete($id,'post_meta');$before_root=null;$before_ref=false;
        foreach($entry['before'] as $row){if($row['meta_key']==='page_modules')$before_root=maybe_unserialize($row['meta_value']);if($row['meta_key']==='_page_modules')$before_ref=true;}
        $current=get_post_meta($id,'page_modules',true);$expected_before=$before_root ?? '';
        $safe=($current===$expected_before||$current===$entry['layouts']);
        foreach($entry['new'] as $key=>$value){if(metadata_exists('post',$id,$key)&&(string)get_post_meta($id,$key,true)!==(string)$value)$safe=false;}
        if(!$safe){echo 'Concurrent changes retained for product '.$id.'; inspect recovery snapshot '.$backup."\n";continue;}
        foreach($entry['new'] as $key=>$value){if(metadata_exists('post',$id,$key))delete_post_meta($id,$key);}
        if(!$before_ref)delete_post_meta($id,'_page_modules');
        if($before_root===null)delete_post_meta($id,'page_modules');else update_post_meta($id,'page_modules',$before_root);
        wp_cache_delete($id,'post_meta');
    }
    wp_cache_flush();throw $e;
}finally{$wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)',$lock));}
echo 'Transferred and verified product values. Recovery snapshot: '.$backup."\n";
