<?php
/**
 * Single posts. Posts built with the previous theme's modules render them in the current design;
 * other posts render their content. A page header, the featured photo and related articles frame both.
 */
get_header();
?>

<main id="main-content">
<?php
while (have_posts()) : the_post();
    $post_id  = get_the_ID();
    $category = ivermectin_get_post_category($post_id);
    $minutes  = ivermectin_reading_minutes($post_id);
    $read_tpl = ivermectin_label('article_read_time_label');

    ob_start();
    ?>
    <?php if ($category !== '' && strtolower($category) !== 'uncategorized') : ?><span class="iva-meta-chip"><?= esc_html($category) ?></span><?php endif; ?>
    <time datetime="<?= esc_attr(get_the_date('c')) ?>"><?= esc_html(get_the_date()) ?></time>
    <?php if ($read_tpl !== '' && $minutes > 0) : ?><span><?= esc_html(ivermectin_fill($read_tpl, ['minutes' => $minutes])) ?></span><?php endif; ?>
    <?php
    $meta = ob_get_clean();

    ivermectin_page_hero([
        'title' => get_the_title(),
        'lead'  => has_excerpt() ? get_the_excerpt() : '',
        'meta'  => $meta,
    ]);

    if (has_post_thumbnail()) : ?>
        <section class="pt-10 md:pt-14">
            <div class="container-site">
                <div class="iva-article-photo"><?php the_post_thumbnail('large', ['class' => 'h-full w-full object-cover', 'alt' => '']); ?></div>
            </div>
        </section>
    <?php endif;

    if (!ivermectin_render_modules($post_id, ['hero' => false])) {
        ivermectin_render_article_section((string) apply_filters('the_content', get_the_content()), true, 'default', true);
    }

    $related_heading = ivermectin_label('article_related_heading');
    if ($related_heading !== '') :
        $related = get_posts([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'post__not_in'   => [$post_id],
            'category__in'   => wp_get_post_categories($post_id),
        ]);
        if (count($related) < 3) {
            $related = get_posts(['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'post__not_in' => [$post_id]]);
        }
        if ($related) : ?>
            <section class="iva-section bg-surface">
                <div class="container-site">
                    <?php ivermectin_section_head('', $related_heading, ''); ?>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-iva-stagger>
                        <?php foreach ($related as $related_post) { ivermectin_render_post_card($related_post, 'grid'); } ?>
                    </div>
                </div>
            </section>
        <?php endif;
    endif;
endwhile;
?>
</main>

<?php get_footer(); ?>
