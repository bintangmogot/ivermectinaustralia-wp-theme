<?php
/** Read-only inventory. Run: wp eval-file tools/audit-module-migration.php */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit("Run through WP-CLI eval-file.\n"); }
if (!function_exists('get_field')) { throw new RuntimeException('ACF must be active.'); }
$shop = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : 0;
$system = array_filter(array_map('intval', [get_option('woocommerce_cart_page_id'), get_option('woocommerce_checkout_page_id'), get_option('woocommerce_myaccount_page_id')]));
$layout_names = static function (string $field, int $id): array {
    // The root flexible-content metadata holds layout names, even if its ACF
    // definition is missing or disabled. Do not rely on formatted field reads.
    $rows = get_post_meta($id, $field, true);
    return is_array($rows) ? array_values(array_filter($rows, 'is_string')) : [];
};
$report = [];
global $wpdb;
// Include every post type/status carrying either parent's metadata, including
// revisions, trash and orphaned subfield metadata. These also need preservation.
$module_ids = $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_key IN (%s,%s,%s,%s) OR meta_key LIKE %s OR meta_key LIKE %s OR meta_key LIKE %s OR meta_key LIKE %s ORDER BY post_id",
    'modules', '_modules', 'page_modules', '_page_modules',
    $wpdb->esc_like('modules_') . '%', $wpdb->esc_like('page_modules_') . '%',
    $wpdb->esc_like('_modules_') . '%', $wpdb->esc_like('_page_modules_') . '%'
));
$content_ids = get_posts(['post_type' => ['page', 'product', 'post', 'city'], 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids']);
$ids = array_unique(array_map('intval', array_merge($module_ids, $content_ids)));
sort($ids);
foreach ($ids as $id) {
    $post = get_post($id);
    if (!$post) {
        $report[] = ['id' => $id, 'review' => 'Orphaned module metadata: preserve and inspect before removing any definition'];
        continue;
    }
    $new = $layout_names('page_modules', $id);
    $old = $layout_names('modules', $id);
    $specs = [];
    if ($post->post_type === 'product') {
        foreach (['product_active_ingredient', 'product_indication', 'product_manufacturer', 'product_packaging', 'product_delivery_time'] as $name) {
            if (trim((string) get_field($name, $id, false)) !== '') { $specs[] = $name; }
        }
    }
    $report[] = [
        'id' => $id, 'type' => $post->post_type, 'status' => $post->post_status, 'title' => $post->post_title,
        'page_modules' => $new, 'modules' => $old,
        'active_parent' => $new ? 'page_modules' : ($old ? 'modules' : 'native_editor'),
        'both_populated' => (bool) ($new && $old),
        'native_content_bytes' => strlen($post->post_content),
        'populated_spec_fields' => $specs,
        'review' => in_array($id, $system, true) ? 'Preserve native WooCommerce system page'
            : ($id === $shop ? 'Merge Shop header, editor and modules; preserve catalogue'
            : ($new && $old ? 'Compare both sources before merging; do not import inactive copy blindly'
            : ($post->post_type === 'product' ? 'Preserve product summary, description, specs and FAQ sources'
            : ($old ? 'Map legacy layouts into Page Modules' : 'Keep existing Page Modules or map native editor')))),
    ];
}
echo wp_json_encode([
    'mode' => 'read-only', 'source_url' => home_url('/'), 'target_parent' => 'page_modules',
    'summary' => [
        'records' => count($report),
        'with_legacy_modules' => count(array_filter($report, static fn($row) => !empty($row['modules']))),
        'both_populated' => count(array_filter($report, static fn($row) => !empty($row['both_populated']))),
    ],
    'posts' => $report,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
