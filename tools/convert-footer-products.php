<?php
/** Convert Best Sellers custom links to WooCommerce product menu items. Default: preview. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit("Use WP-CLI eval-file.\n"); }
$mode = $args[0] ?? 'preview';
if (!in_array($mode, ['preview', 'apply'], true)) { throw new RuntimeException('Use preview or apply.'); }
$locations = get_nav_menu_locations();
$menu_id = (int) ($locations['footer_trending'] ?? 0);
if (!$menu_id) { throw new RuntimeException('Assign the Best Sellers menu to Footer Trending Products first.'); }
$items = wp_get_nav_menu_items($menu_id);
if (!is_array($items)) { throw new RuntimeException('Could not read the footer menu.'); }
$products = get_posts(['post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1]);
$identity = static function (string $name): ?array {
    if (!preg_match('/^\s*([a-z]+)\b.*?\b(\d+)\s*mg\b/i', $name, $m)) { return null; }
    return [strtolower($m[1]), (int) $m[2]];
};
$index = [];
foreach ($products as $product) {
    $key = $identity($product->post_title);
    if ($key) { $index[implode(':', $key)][] = $product; }
}
$plan = []; $conflicts = [];
foreach ($items as $item) {
    if ($item->type === 'post_type' && $item->object === 'product') { continue; }
    if ($item->type !== 'custom') { $conflicts[] = "{$item->title}: not a custom link or product."; continue; }
    $old_path = untrailingslashit((string) wp_parse_url($item->url, PHP_URL_PATH));
    $matches = [];
    if ($old_path !== '') {
        foreach ($products as $product) {
            $product_path = untrailingslashit((string) wp_parse_url(get_permalink($product->ID), PHP_URL_PATH));
            if ($old_path === $product_path) { $matches[] = $product; }
        }
    }
    if (!$matches) {
        $key = $identity($item->title);
        $matches = $key ? ($index[implode(':', $key)] ?? []) : [];
    }
    if (count($matches) !== 1) { $conflicts[] = "{$item->title}: expected one matching published product, found " . count($matches) . '.'; continue; }
    $product = $matches[0];
    $plan[] = ['menu_item_id' => (int) $item->ID, 'label' => $item->title, 'product_id' => (int) $product->ID, 'product' => $product->post_title, 'old_url' => $item->url, 'product_url' => get_permalink($product->ID)];
}
echo wp_json_encode(['mode'=>$mode, 'site'=>home_url('/'), 'menu_id'=>$menu_id, 'items_to_convert'=>$plan, 'conflicts'=>$conflicts], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . "\n";
if ($mode === 'preview') { return; }
if (!current_user_can('manage_options')) { throw new RuntimeException('Run with WP-CLI --user=<administrator>.'); }
if ($conflicts) { throw new RuntimeException('Preflight conflicts: no items were changed.'); }
$snapshot=[];
foreach ($plan as $row) {
    $id=$row['menu_item_id'];
    foreach (['_menu_item_type','_menu_item_object','_menu_item_object_id','_menu_item_url'] as $key) { $snapshot[$id][$key]=['exists'=>metadata_exists('post',$id,$key),'value'=>get_post_meta($id,$key,true)]; }
}
$path=tempnam(sys_get_temp_dir(),'iva-footer-products-');
if (!$path || file_put_contents($path,wp_json_encode($snapshot))===false) { throw new RuntimeException('Could not save a recovery snapshot.'); }
@chmod($path,0600);
$written=[];
try {
    foreach ($plan as $row) {
        $id=$row['menu_item_id'];
        foreach ($snapshot[$id] as $key=>$old) { if (metadata_exists('post',$id,$key)!==$old['exists'] || get_post_meta($id,$key,true)!==$old['value']) { throw new RuntimeException('A menu item changed during transfer.'); } }
        foreach (['_menu_item_type'=>'post_type','_menu_item_object'=>'product','_menu_item_object_id'=>(string)$row['product_id'],'_menu_item_url'=>''] as $key=>$value) {
            update_post_meta($id,$key,$value);
            $written[$id][$key]=(string)$value;
            if ((string)get_post_meta($id,$key,true)!==(string)$value) { throw new RuntimeException("Could not update $key for item $id."); }
        }
    }
    $after=wp_get_nav_menu_items($menu_id); $by_id=[];
    foreach ((array)$after as $item) { $by_id[(int)$item->ID]=$item; }
    foreach ($plan as $row) {
        $item=$by_id[$row['menu_item_id']]??null;
        if (!$item || $item->type!=='post_type' || $item->object!=='product' || (int)$item->object_id!==$row['product_id'] || $item->title!==$row['label'] || $item->url!==$row['product_url']) { throw new RuntimeException('Product menu readback did not match.'); }
    }
} catch (Throwable $e) {
    foreach ($written as $id=>$values) {
        foreach ($values as $key=>$value) {
            if ((string)get_post_meta((int)$id,$key,true)!==$value) { continue; }
            $old=$snapshot[$id][$key];
            if ($old['exists']) { update_post_meta((int)$id,$key,$old['value']); }
            else { delete_post_meta((int)$id,$key); }
        }
    }
    throw $e;
}
echo "Converted and verified product menu items. Recovery snapshot: $path\n";
