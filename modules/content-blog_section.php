<?php
/**
 * Module: blog_section (also used by blog_grid).
 * Sub fields: eyebrow, title, subtitle, selected_posts, posts_count, show_view_all, button_text, button_url.
 * Chosen posts are shown, otherwise the latest posts. Empty text fields render nothing.
 * Cards: inset photo with the category chip, title, excerpt, date and an arrow link.
 * Excerpt: the post's Excerpt box when filled, otherwise the opening words of the article (post content, or the
 * first text block of the previous theme's modules field, where these posts keep their text).
 */
$tag         = trim((string) get_sub_field('eyebrow'));
$heading     = trim((string) get_sub_field('title'));
$description = trim((string) get_sub_field('subtitle'));
$button_text = trim((string) get_sub_field('button_text'));
$button_url  = trim((string) get_sub_field('button_url'));
$posts_count = (int) get_sub_field('posts_count') ?: (is_front_page() ? 3 : 9);
$show_all    = (bool) get_sub_field('show_view_all');
$selected    = get_sub_field('selected_posts');

if ($button_url === '') {
    $blog_page_id = (int) get_option('page_for_posts');
    $button_url   = $blog_page_id ? (string) get_permalink($blog_page_id) : '';
}
if (is_home() || is_archive()) {
    $show_all = false;
}

$articles = [];
if (is_array($selected)) {
    foreach ($selected as $item) {
        $post_object = is_numeric($item) ? get_post((int) $item) : $item;
        if ($post_object instanceof WP_Post && $post_object->post_status === 'publish') {
            $articles[] = $post_object;
        }
    }
}
if (!$articles) {
    $articles = get_posts(['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $posts_count]);
}
if (!$articles) {
    return;
}
?>
<?php
// With exactly three posts the newest is featured large and the other two stack beside it.
$featured = count($articles) === 3;
?>
<section class="iva-section bg-surface" data-testid="blog-section">
    <div class="container-site">
        <?php ivermectin_section_head($tag, $heading, $description); ?>

        <?php if ($featured) : ?>
            <div class="iva-posts-featured" data-iva-stagger>
                <?php ivermectin_render_post_card($articles[0], 'large'); ?>
                <div class="grid gap-5">
                    <?php ivermectin_render_post_card($articles[1], 'side'); ?>
                    <?php ivermectin_render_post_card($articles[2], 'side'); ?>
                </div>
            </div>
        <?php else : ?>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-iva-stagger>
                <?php foreach ($articles as $article) { ivermectin_render_post_card($article, 'grid'); } ?>
            </div>
        <?php endif; ?>

        <?php if ($show_all && $button_text !== '' && $button_url !== '') : ?>
            <div class="mt-12 text-center">
                <a href="<?= esc_url($button_url) ?>" class="iva-btn-pill"><?= esc_html($button_text) ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>
