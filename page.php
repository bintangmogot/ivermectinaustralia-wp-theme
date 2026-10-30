<?php
/**
 * Pages. New pages use "page_modules". Pages built with the previous theme keep their "modules".
 */
get_header();
?>

<main id="main-content">
<?php
if (!ivermectin_render_modules()) :
    $is_shop_flow = class_exists('WooCommerce') && (is_cart() || is_checkout() || is_account_page());
    while (have_posts()) : the_post();
        if (!$is_shop_flow) : ?>
            <header class="relative isolate overflow-hidden bg-primary text-white">
                <?php ivermectin_output_pattern_rings(); ?>
                <div class="container-site relative z-10 py-8 md:py-10">
                    <nav aria-label="Breadcrumb" class="mb-2 text-sm text-white/70">
                        <a href="<?= esc_url(home_url('/')) ?>" class="hover:text-white"><?= esc_html__('Home', 'ivermectin-australia') ?></a>
                        <span aria-hidden="true" class="mx-1.5">/</span>
                        <span aria-current="page" class="text-white"><?php the_title(); ?></span>
                    </nav>
                    <h1 class="font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl md:text-5xl"><?php the_title(); ?></h1>
                </div>
            </header>
        <?php endif; ?>
        <div class="py-8 md:py-10">
            <div class="container-site <?= $is_shop_flow ? 'max-w-7xl' : 'max-w-4xl' ?>">
                <div class="prose max-w-none"><?php the_content(); ?></div>
            </div>
        </div>
    <?php endwhile;
endif;
?>
</main>

<?php get_footer(); ?>
