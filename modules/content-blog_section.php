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
if (!function_exists('ivermectin_post_card_excerpt')) {
    function ivermectin_post_card_excerpt(WP_Post $article, int $words): string {
        if (has_excerpt($article)) {
            return wp_trim_words(get_the_excerpt($article), $words);
        }
        $text = wp_strip_all_tags(strip_shortcodes($article->post_content));
        if (trim($text) === '') {
            foreach ((array) get_post_meta($article->ID, 'modules', true) as $index => $layout) {
                $block = (string) get_post_meta($article->ID, "modules_{$index}_content", true);
                if (is_string($layout) && str_contains($layout, 'text') && trim(wp_strip_all_tags($block)) !== '') {
                    $text = wp_strip_all_tags(strip_shortcodes($block));
                    break;
                }
            }
        }
        return wp_trim_words($text, $words);
    }
}

// With exactly three posts the newest is featured large and the other two stack beside it.
$featured = count($articles) === 3;
$card     = static function (WP_Post $article, string $variant): void {
    $link       = get_permalink($article);
    $categories = get_the_category($article->ID);
    $category   = $categories && $categories[0]->slug !== 'uncategorized' ? $categories[0]->name : '';
    $excerpt    = ivermectin_post_card_excerpt($article, $variant === 'large' ? 34 : 18);
    $title      = get_the_title($article);
    ?>
    <article class="iva-post iva-post-<?= esc_attr($variant) ?> group">
        <?php if (has_post_thumbnail($article)) : ?>
            <a href="<?= esc_url($link) ?>" class="iva-post-media" tabindex="-1" aria-hidden="true">
                <?= get_the_post_thumbnail($article, $variant === 'side' ? 'medium' : 'medium_large', ['class' => 'h-full w-full object-cover', 'alt' => '']) ?>
                <?php if ($category !== '') : ?><span class="iva-post-chip"><?= esc_html($category) ?></span><?php endif; ?>
            </a>
        <?php endif; ?>
        <div class="iva-post-body">
            <h3 class="iva-post-title"><a href="<?= esc_url($link) ?>"><span><?= esc_html($title) ?></span></a></h3>
            <?php if ($excerpt !== '') : ?><p class="iva-post-excerpt"><?= esc_html($excerpt) ?></p><?php endif; ?>
            <div class="iva-post-foot">
                <time class="inline-flex items-center gap-2" datetime="<?= esc_attr(get_the_date('c', $article)) ?>">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="3"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg>
                    <?= esc_html(get_the_date('', $article)) ?>
                </time>
                <a href="<?= esc_url($link) ?>" class="iva-post-arrow" aria-label="<?= esc_attr($title) ?>">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </a>
            </div>
        </div>
    </article>
    <?php
};
?>
<section class="iva-section bg-surface" data-testid="blog-section">
    <div class="container-site">
        <?php ivermectin_section_head($tag, $heading, $description); ?>

        <?php if ($featured) : ?>
            <div class="iva-posts-featured" data-iva-stagger>
                <?php $card($articles[0], 'large'); ?>
                <div class="grid gap-5">
                    <?php $card($articles[1], 'side'); ?>
                    <?php $card($articles[2], 'side'); ?>
                </div>
            </div>
        <?php else : ?>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-iva-stagger>
                <?php foreach ($articles as $article) { $card($article, 'grid'); } ?>
            </div>
        <?php endif; ?>

        <?php if ($show_all && $button_text !== '' && $button_url !== '') : ?>
            <div class="mt-12 text-center">
                <a href="<?= esc_url($button_url) ?>" class="iva-btn-pill"><?= esc_html($button_text) ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>
