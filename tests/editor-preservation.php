<?php
/** Local CLI regression: content panels remain available and saved blanks are not seeded again. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['REQUEST_SCHEME'] = 'http'; $_SERVER['HTTP_HOST'] = 'ivermectinaustralia.com.au.test';
define('DISABLE_WP_CRON', true);
require dirname(__DIR__, 4) . '/wp-load.php';
set_exception_handler(static function (Throwable $error): void { fwrite(STDERR, $error->getMessage() . "\n"); exit(1); });
require_once get_theme_file_path('inc/admin-notes.php');
$assert = static function (bool $ok, string $message): void { if (!$ok) { throw new RuntimeException($message); } };
$id = wp_insert_post(['post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'Editor preservation fixture']);
$front = get_option('page_on_front');
$saved_hash = get_option('ivermectin_homepage_seed_hash', null);
try {
    $before = ['acf-group_page_modules', 'acf-group_68adc05fe2e0a', 'acf-group_product_details', 'slugdiv'];
    $visible = apply_filters('hidden_meta_boxes', $before, (object) ['post_type' => 'page'], false);
    $assert($visible === ['slugdiv'], 'Content panels remain hidden through Screen Options');
    $other = apply_filters('hidden_meta_boxes', $before, (object) ['post_type' => 'post'], false);
    $assert($other === $before, 'Unrelated editor preferences were changed');
    update_option('page_on_front', $id);
    update_field('field_iva_page_modules', [], $id);
    $snapshot = get_post_meta($id);
    ivermectin_seed_homepage();
    $assert(get_post_meta($id) === $snapshot, 'A deliberately cleared homepage was refilled');
    $assert(get_option('ivermectin_homepage_seed_hash', null) === $saved_hash, 'The seed marker changed for an existing homepage');
    $source = file_get_contents(get_theme_file_path('inc/admin-notes.php'));
    $assert(strpos($source, 'display: none') === false, 'Content panel hiding was reintroduced');
    echo "PASS: ACF panels visible, unrelated preferences preserved, saved empty modules unchanged.\n";
} finally {
    update_option('page_on_front', $front);
    wp_delete_post($id, true);
}
