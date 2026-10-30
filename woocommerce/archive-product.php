<?php
/**
 * Product archive and shop page.
 * Shop page modules ("page_modules", or the previous theme's "modules") render first, then the product grid.
 */

defined('ABSPATH') || exit;

get_header('shop');

$shop_page_id  = wc_get_page_id('shop');
$has_modules   = $shop_page_id > 0 && (have_rows('page_modules', $shop_page_id) || have_rows('modules', $shop_page_id));
$has_new       = $shop_page_id > 0 && have_rows('page_modules', $shop_page_id);
$intro_text    = $shop_page_id > 0 ? (string) get_field('shop_page_text', $shop_page_id) : '';

echo '<main id="main-content">';

if ($has_modules && is_shop()) {
    ivermectin_render_modules($shop_page_id);
}

if (!$has_new || !is_shop()) :
    if (!$has_modules || !is_shop()) : ?>
        <header class="relative isolate overflow-hidden bg-primary text-white">
            <?php ivermectin_output_pattern_rings(); ?>
            <div class="container-site relative z-10 py-8 md:py-10">
                <h1 class="font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl md:text-5xl"><?= esc_html(woocommerce_page_title(false)) ?></h1>
                <?php if ($intro_text !== '') : ?><div class="prose-invert mt-3 max-w-2xl text-white/85"><?= wp_kses_post($intro_text) ?></div><?php endif; ?>
            </div>
        </header>
    <?php endif; ?>

    <section class="py-8 md:py-10">
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
                do_action('woocommerce_after_shop_loop');
            } else {
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>
    </section>
<?php endif;

echo '</main>';

get_footer('shop');
