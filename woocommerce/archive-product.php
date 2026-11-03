<?php
/**
 * Product archive and shop page.
 * A shop page built with "page_modules" renders those modules. Otherwise: page header, the product grid,
 * then the shop page's content from the previous theme (FAQs and text) below the products.
 */

defined('ABSPATH') || exit;

get_header('shop');

$shop_page_id = wc_get_page_id('shop');
$has_new      = $shop_page_id > 0 && is_shop() && have_rows('page_modules', $shop_page_id);
$intro_text   = $shop_page_id > 0 && is_shop() ? (string) get_field('shop_page_text', $shop_page_id) : '';

echo '<main id="main-content">';

if ($has_new) {
    ivermectin_render_modules($shop_page_id);
} else {
    ivermectin_page_hero([
        'title' => woocommerce_page_title(false),
        'lead'  => $intro_text !== '' ? $intro_text : (is_shop() ? '' : term_description()),
    ] + ivermectin_hero_variant_args('shop'));
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
