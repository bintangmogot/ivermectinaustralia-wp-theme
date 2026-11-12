<?php
/**
 * Blog index, category and tag archives, and search results.
 * A posts page built with "page_modules" renders those modules; its article list shows where a
 * posts grid module sits, or after the page header.
 */

get_header();

$blog_page_id = is_home() ? (int) get_option('page_for_posts') : 0;
$has_modules  = $blog_page_id && !ivermectin_uses_transferred_modules($blog_page_id) && have_rows('page_modules', $blog_page_id);

/** The article list: category chips, cards, page numbers, or an empty state. */
function ivermectin_render_blog_loop() {
    ?>
    <section id="blog-articles" class="iva-section iva-blog-index bg-surface" aria-label="<?= esc_attr(ivermectin_label('archive_default_title')) ?>">
        <div class="container-site">
            <?php ivermectin_render_category_chips(); ?>
            <?php if (have_posts()) : ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-iva-stagger>
                    <?php while (have_posts()) { the_post(); ivermectin_render_post_card(get_post(), 'grid'); } ?>
                </div>
                <?= ivermectin_main_pagination() ?>
            <?php else : ?>
                <div class="iva-empty">
                    <h2 class="iva-h3"><?= esc_html(is_search() ? ivermectin_label('search_empty_heading') : ivermectin_label('empty_posts_heading')) ?></h2>
                    <p class="iva-body mt-2"><?= esc_html(is_search() ? ivermectin_label('search_empty_text') : ivermectin_label('empty_posts_text')) ?></p>
                    <?php if (is_search()) : ?><div class="mx-auto mt-6 max-w-md"><?php get_search_form(); ?></div><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

echo '<main id="main-content">';

if (is_home() && $has_modules) {
    $rendered_loop = false;
    $layouts       = array_column(get_field('page_modules', $blog_page_id) ?: [], 'acf_fc_layout');
    $has_grid      = in_array('posts_grid', $layouts, true) || in_array('blog_grid', $layouts, true);
    while (have_rows('page_modules', $blog_page_id)) {
        the_row();
        $layout = get_row_layout();
        if ($layout === 'posts_grid' || $layout === 'blog_grid') {
            ivermectin_render_blog_loop();
            $rendered_loop = true;
        } else {
            ivermectin_render_page_module($layout);
            if ($layout === 'page_hero' && !$has_grid && !$rendered_loop) {
                ivermectin_render_blog_loop();
                $rendered_loop = true;
            }
        }
    }
    if (!$rendered_loop) {
        ivermectin_render_blog_loop();
    }
} else {
    $title = ivermectin_label('archive_default_title');
    $lead  = '';
    if (is_archive()) {
        $archive_title = get_the_archive_title();
        $title         = $archive_title ? wp_strip_all_tags($archive_title) : $title;
        $lead          = get_the_archive_description();
    } elseif (is_search()) {
        $title = ivermectin_fill(ivermectin_label('search_title'), ['query' => get_search_query()]);
    }
    ivermectin_page_hero(['title' => $title, 'lead' => $lead] + ivermectin_hero_variant_args('search'));
    ivermectin_render_blog_loop();
}

echo '</main>';

get_footer();
