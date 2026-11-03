<?php
/**
 * Pages. New pages use "page_modules". Pages built with the previous theme keep their "modules",
 * rendered in the current design with their page header. Other pages show the page title and content.
 */
get_header();
?>

<main id="main-content">
<?php
if (!ivermectin_render_modules()) :
    $is_shop_flow = class_exists('WooCommerce') && (is_cart() || is_checkout() || is_account_page());
    while (have_posts()) : the_post();
        ivermectin_page_hero(['title' => get_the_title()]);
        if ($is_shop_flow) : ?>
            <section class="iva-section-tight bg-white">
                <div class="container-site max-w-7xl">
                    <div class="iva-woo"><?php the_content(); ?></div>
                </div>
            </section>
        <?php else :
            ivermectin_render_article_section((string) apply_filters('the_content', get_the_content()), true, 'default', true);
        endif;
    endwhile;
endif;
?>
</main>

<?php get_footer(); ?>
