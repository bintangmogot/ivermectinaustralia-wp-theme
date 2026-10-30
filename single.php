<?php
/**
 * Single posts. Posts built with modules render them, other posts render a standard article.
 */
get_header();
?>

<main id="main-content">
<?php
if (!ivermectin_render_modules()) :
    while (have_posts()) : the_post();
        $category = ivermectin_get_post_category(get_the_ID());
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="relative isolate overflow-hidden bg-primary text-white">
                <?php ivermectin_output_pattern_rings(); ?>
                <div class="container-site relative z-10 max-w-4xl py-8 md:py-10">
                    <?php if ($category !== '') : ?><p class="mb-2 text-sm font-semibold uppercase tracking-wider text-white/70"><?= esc_html($category) ?></p><?php endif; ?>
                    <h1 class="font-heading text-3xl font-extrabold leading-tight text-white sm:text-4xl md:text-5xl"><?php the_title(); ?></h1>
                    <p class="mt-3 text-sm text-white/70"><time datetime="<?= esc_attr(get_the_date('c')) ?>"><?= esc_html(get_the_date('j F Y')) ?></time></p>
                </div>
            </header>
            <div class="py-8 md:py-10">
                <div class="container-site max-w-4xl">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="mb-6 overflow-hidden rounded-xl border border-border"><?php the_post_thumbnail('large', ['class' => 'h-auto w-full']); ?></div>
                    <?php endif; ?>
                    <div class="prose max-w-none"><?php the_content(); ?></div>
                </div>
            </div>
        </article>
    <?php endwhile;
endif;
?>
</main>

<?php get_footer(); ?>
