<?php
/** Read-only inventory. Run: wp eval-file tools/audit-module-migration.php */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit("Run through WP-CLI eval-file.\n"); }
if (!function_exists('get_field')) { throw new RuntimeException('ACF must be active.'); }
$shop = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : 0;
$system = array_filter(array_map('intval', [get_option('woocommerce_cart_page_id'), get_option('woocommerce_checkout_page_id'), get_option('woocommerce_myaccount_page_id')]));
$layout_names = static function (string $field, int $id): array {
    $rows = get_field($field, $id, false);
    return is_array($rows) ? array_values(array_filter(array_column($rows, 'acf_fc_layout'))) : [];
};
$report = [];
foreach (get_posts(['post_type' => ['page', 'product'], 'post_status' => ['publish', 'draft', 'private', 'pending'], 'posts_per_page' => -1]) as $post) {
    $id = (int) $post->ID;
    $new = $layout_names('page_modules', $id);
    $old = $layout_names('modules', $id);
    $specs = [];
    if ($post->post_type === 'product') {
        foreach (['product_active_ingredient', 'product_indication', 'product_manufacturer', 'product_packaging', 'product_delivery_time'] as $name) {
            if (trim((string) get_field($name, $id, false)) !== '') { $specs[] = $name; }
        }
    }
    $report[] = [
        'id' => $id, 'type' => $post->post_type, 'title' => $post->post_title,
        'page_modules' => $new, 'modules' => $old,
        'native_content_bytes' => strlen($post->post_content),
        'populated_spec_fields' => $specs,
        'review' => in_array($id, $system, true) ? 'Preserve native WooCommerce system page'
            : ($id === $shop ? 'Merge Shop header, editor and modules; preserve catalogue'
            : ($new && $old ? 'Compare both sources before merging; do not import inactive copy blindly'
            : ($post->post_type === 'product' ? 'Preserve product summary, description, specs and FAQ sources'
            : ($old ? 'Map legacy layouts into Page Modules' : 'Keep existing Page Modules or map native editor')))),
    ];
}
echo wp_json_encode(['mode' => 'read-only', 'target_parent' => 'page_modules', 'posts' => $report], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
