<?php
/**
 * The template for displaying all pages
 * Waklert Australia 2-tone Dashed & Patterned Theme
 */

get_header();
?>

<main>
    <?php
    // If the page uses ACF Flexible Content Modules, render them:
    if (have_rows('modules')) {
        while (have_rows('modules')) {
            the_row();
            $layout = get_row_layout();
            // This will look for modules/content-{layout_name}.php
            get_template_part('modules/content', $layout);
        }
    } 
    // Otherwise, render a standard text page:
    else {
        ?>
        <?php if ( ! ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) ) : ?>
        <!-- 2-Tone Patterned Header for Standard Pages -->
        <header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-6 sm:py-7 md:py-9">
            <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
                <!-- Breadcrumbs -->
                <div class="mb-3">
                    <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                        <a href="<?= esc_url(home_url('/')) ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                        <span class="text-sky-300 font-normal">/</span>
                        <span aria-current="page" class="font-extrabold text-[#0c4161]"><?php the_title(); ?></span>
                    </nav>
                </div>

                <!-- Eyebrow Badge -->
                <div class="mb-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3.5 py-1 text-xs font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                        Waklert Australia
                    </span>
                </div>

                <h1 class="mx-auto max-w-3xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-tight tracking-tight text-[#0c4161]">
                    <?php the_title(); ?>
                </h1>
            </div>
        </header>
        <?php endif; ?>

        <?php if ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) : ?>
        <div class="section-padding bg-background min-h-[50vh]">
            <div class="container-custom max-w-7xl">
                <?php
                while (have_posts()) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </div>
        </div>
        <?php else : ?>
        <div class="py-7 md:py-10 bg-gradient-to-b from-white via-slate-50/50 to-white min-h-[50vh]">
            <div class="container-site max-w-4xl">
                <div class="rounded-3xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-10 shadow-xs">
                    <div class="prose max-w-none text-slate-600 prose-headings:font-heading prose-headings:text-[#0c4161] prose-headings:font-black prose-a:text-[#0c4161] hover:prose-a:underline">
                        <?php
                        while (have_posts()) :
                            the_post();
                            the_content();
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php
    }
    ?>
</main>

<?php
get_footer();
