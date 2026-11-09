<?php
/** Run with PHP CLI against the local WordPress installation. Uses a disposable draft page. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['HTTP_HOST'] = 'ivermectinaustralia.com.au.test';
define('DISABLE_WP_CRON', true);
require dirname(__DIR__, 4) . '/wp-load.php';
set_exception_handler(static function (Throwable $error): void { fwrite(STDERR, $error->getMessage() . "\n" . $error->getTraceAsString() . "\n"); exit(1); });
add_filter('pre_wp_mail', '__return_true');

$fixture = wp_insert_post(['post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'Shop render fixture', 'post_content' => '<h1>Editor heading fixture</h1><p>Editor intro fixture.</p><h2>Editor details fixture</h2><p>Editor body fixture.</p>'], true);
if (is_wp_error($fixture)) { throw new RuntimeException($fixture->get_error_message()); }
add_filter('option_woocommerce_shop_page_id', static fn() => $fixture);
$render = static function (): string {
    global $wp_query, $wp_the_query, $post;
    $wp_query = new WP_Query(['post_type' => 'product', 'posts_per_page' => 2]);
    $wp_query->is_post_type_archive = true;
    $wp_query->is_archive = true;
    $wp_the_query = $wp_query;
    $post = $wp_query->post;
    wc_set_loop_prop('total', (int) $wp_query->found_posts);
    $level = ob_get_level();
    ob_start();
    try {
        wc_get_template('archive-product.php');
        preg_match('~<main id="main-content">(.*)</main>~s', (string) ob_get_contents(), $main);
        return $main[1] ?? '';
    }
    finally { while (ob_get_level() > $level) { ob_end_clean(); } }
};
$check = static function (bool $ok, string $message): void { if (!$ok) { throw new RuntimeException($message); } };
try {
    $html = $render();
    $check((bool) preg_match('/<h1[^>]*>Editor heading fixture<\/h1>/', $html), 'Native editor H1 is missing');
    $check(strpos($html, 'Editor intro fixture.') !== false && strpos($html, 'Editor body fixture.') < strpos($html, 'id="products"'), 'Native editor content must precede the catalogue');
    $check(substr_count($html, 'Editor intro fixture.') === 1, 'Intro was repeated');
    $check(strpos($html, 'iva-prow') !== false, 'Product loop was lost');
    update_field('field_iva_shop_heading', 'ACF heading fixture', $fixture);
    update_field('field_iva_shop_intro', '<p>ACF intro fixture.</p>', $fixture);
    $html = $render();
    $check((bool) preg_match('/<h1[^>]*>ACF heading fixture<\/h1>/', $html), 'Explicit ACF heading must take priority');
    $check(strpos($html, 'ACF intro fixture.') !== false && strpos($html, 'Editor intro fixture.') !== false && strpos($html, 'Editor body fixture.') !== false, 'ACF overrides discarded saved native text');
    $check(get_post_field('post_content', $fixture) === '<h1>Editor heading fixture</h1><p>Editor intro fixture.</p><h2>Editor details fixture</h2><p>Editor body fixture.</p>', 'Rendering altered saved content');
    echo "PASS: native Shop heading, intro and body render; ACF overrides retain native text; products and saved content are preserved.\n";
} finally { wp_delete_post($fixture, true); }
