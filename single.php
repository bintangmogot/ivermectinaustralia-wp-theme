<?php
/**
 * The template for displaying individual blog posts.
 */

get_header();
?>

<main>
    <?php
    // Check if the post uses ACF Flexible Content Modules (like the policy layout)
    if (have_rows('modules')) {
        while (have_rows('modules')) {
            the_row();
            $layout = get_row_layout();
            get_template_part('modules/content', $layout);
        }
    } else {
        // Fallback to standard post layout
        while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="relative overflow-hidden border-b-2 border-dashed border-sky-300/80 bg-gradient-to-b from-[#f0f6fa] via-[#eef5fa] to-white bg-pattern-dots text-center py-5 sm:py-6 md:py-7" data-testid="single-post-hero">
                    <div class="container-site max-w-4xl mx-auto px-4 relative z-10">
                        <!-- Centered Breadcrumb Pill -->
                        <div class="mb-2.5">
                            <nav aria-label="Breadcrumb" class="inline-flex max-w-full flex-wrap items-center justify-center gap-1.5 rounded-full border border-dashed border-sky-300/80 bg-white/90 px-3.5 py-1 text-xs font-medium text-slate-500 shadow-xs backdrop-blur">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Home</a>
                                <span class="text-sky-300 font-normal">/</span>
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="font-bold text-slate-600 transition-colors hover:text-[#0c4161]">Blog</a>
                                <span class="text-sky-300 font-normal">/</span>
                                <span class="max-w-[12rem] sm:max-w-xs md:max-w-md truncate font-extrabold text-[#0c4161]"><?php the_title(); ?></span>
                            </nav>
                        </div>

                        <!-- Eyebrow Badge -->
                        <?php $category = modmy_get_post_category(get_the_ID()); ?>
                        <div class="mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100/90 border border-sky-300/80 px-3 py-0.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c4161] shadow-xs">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>
                                <?php echo esc_html($category ?: 'Waklert Australia Insights'); ?>
                            </span>
                        </div>

                        <!-- Compact Centered Title -->
                        <h1 class="mx-auto max-w-3xl md:max-w-4xl lg:max-w-5xl font-heading text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-[3.25rem] font-black leading-[1.15] tracking-tight text-[#0c4161]">
                            <?php the_title(); ?>
                        </h1>

                        <!-- Meta info: Date & Author -->
                        <?php
                        $post_author_id = get_post_field('post_author', get_the_ID());
                        $author_name = get_the_author_meta('display_name', $post_author_id);
                        if (empty($author_name) || strtolower($author_name) === 'admin') {
                            $author_name = 'Waklert Australia Team';
                        }
                        ?>
                        <div class="mt-2.5 flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>
                            </span>
                            <span class="text-sky-300" aria-hidden="true">&middot;</span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span><?php echo esc_html($author_name); ?></span>
                            </span>
                        </div>
                    </div>
                </header>

                <div class="bg-background min-h-[50vh] py-6 md:py-10">
                    <div class="container-site max-w-3xl">
                        <?php if (has_post_thumbnail(get_the_ID())) : ?>
                            <figure class="mb-6 sm:mb-8 overflow-hidden rounded-2xl border-2 border-dashed border-sky-200/80 bg-white p-1.5 shadow-sm">
                                <?php echo get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'h-auto w-full object-cover rounded-xl shadow-xs', 'loading' => 'eager']); ?>
                            </figure>
                        <?php endif; ?>

                        <?php 
                        $intro = get_field('intro') ?: get_the_excerpt(get_the_ID());
                        if (!empty($intro)) : ?>
                            <div class="mb-6 sm:mb-8 rounded-2xl sm:rounded-3xl border-2 border-dashed border-sky-200/80 bg-white p-6 sm:p-8 shadow-xs prose max-w-none text-slate-600 text-sm sm:text-base leading-relaxed">
                                <?php echo wp_kses_post(wpautop($intro)); ?>
                            </div>
                        <?php endif; ?>

                        <div class="prose prose-slate max-w-none prose-a:text-primary hover:prose-a:text-primary-dark prose-headings:font-heading prose-headings:font-bold">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </article>
    <?php endwhile; 
    } // End else
    ?>
</main>

<?php get_footer(); ?>