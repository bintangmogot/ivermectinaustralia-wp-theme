<?php
/**
 * Product archive and shop page.
 * A shop page built with "page_modules" renders those modules. Otherwise: page header, the product grid,
 * native editor text above the products, and legacy modules (FAQs and text) below them.
 */

defined('ABSPATH') || exit;

get_header('shop');

$shop_page_id = wc_get_page_id('shop');
$has_new      = $shop_page_id > 0 && is_shop() && have_rows('page_modules', $shop_page_id);
$intro_text   = $shop_page_id > 0 && is_shop() ? (string) get_field('shop_page_text', $shop_page_id) : '';
$shop_heading = $shop_page_id > 0 && is_shop() ? trim((string) get_field('shop_page_heading', $shop_page_id)) : '';

echo '<main id="main-content">';

if ($has_new) {
    ivermectin_render_modules($shop_page_id);
} else {
    $editor = ['title' => '', 'lead' => '', 'rest' => ''];
    if (is_shop() && $shop_page_id > 0) {
        $shop_post = get_post($shop_page_id);
        if ($shop_post && trim($shop_post->post_content) !== '') {
            // Render blocks and shortcodes in the Shop page context, then restore the product query.
            $GLOBALS['post'] = $shop_post;
            setup_postdata($shop_post);
            try {
                $editor = ivermectin_split_leading_heading((string) apply_filters('the_content', $shop_post->post_content));
            } finally {
                wp_reset_postdata();
            }
        }
    }
    ivermectin_page_hero([
        'title' => $shop_heading !== '' ? $shop_heading : ($editor['title'] !== '' ? $editor['title'] : woocommerce_page_title(false)),
        'lead'  => $intro_text !== '' ? $intro_text : (is_shop() ? $editor['lead'] : term_description()),
    ] + ivermectin_hero_variant_args('shop'));
    // Preserve distinct editor text, but show an intro only once when both editors contain the same copy.
    $plain_text = static fn(string $html): string => trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(wp_strip_all_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    $repeat_intro = $intro_text !== '' && $plain_text($intro_text) === $plain_text($editor['lead']);
    $editor_body = ($intro_text !== '' && !$repeat_intro ? $editor['lead'] : '') . $editor['rest'];
    if (trim(wp_strip_all_tags($editor_body)) !== '' || preg_match('/<(img|video|audio|iframe|figure|table|form)\b/i', $editor_body)) {
        ivermectin_render_article_section(ivermectin_demote_h1($editor_body), true, 'default', true);
    }
    ?>
    <section class="iva-section-tight bg-white" id="products">
        <div class="container-site">
            <?php
            if (woocommerce_product_loop()) {
                woocommerce_product_loop_start();
                if (wc_get_loop_prop('total')) {
                    while (have_posts()) {
                        the_post();
                        do_action('woocommerce_shop_loop');
                        wc_get_template_part('content', 'product');
                    }
                }
                woocommerce_product_loop_end();
                echo ivermectin_main_pagination();
            } else {
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>
    </section>
    <?php
    if (is_shop() && $shop_page_id > 0) {
        ivermectin_render_modules($shop_page_id, ['hero' => false]);
    }
}

echo '</main>';

get_footer('shop');
