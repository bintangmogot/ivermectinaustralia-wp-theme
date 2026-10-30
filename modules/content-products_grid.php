<?php
/**
 * Module: Products Grid
 * Displays the WooCommerce products loop.
 */
?>
<section class="relative overflow-hidden bg-gradient-to-b from-[#f0f6fa] via-[#e9f2f8] to-[#f8fafc] bg-pattern-dots py-8 md:py-12">
    <div class="container-site px-3 sm:px-4 relative z-10">
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
        } else {
            do_action('woocommerce_no_products_found');
        }
        ?>
    </div>
</section>
