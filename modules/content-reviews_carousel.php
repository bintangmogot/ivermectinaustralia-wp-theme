<?php
/**
 * Module: reviews_carousel. Sub fields: title, reviews (relationship to the Reviews post type).
 * Selected reviews are shown, otherwise the latest published reviews. The score and count
 * are calculated from real review posts and shown beside the heading. With no reviews the module renders nothing.
 */
$heading  = trim((string) get_sub_field('title'));
$labels   = [];
foreach (['score_text', 'verified_label', 'rating_label', 'previous_label', 'next_label'] as $label_name) {
    $labels[$label_name] = trim((string) get_sub_field($label_name));
}
$selected = get_sub_field('reviews');

$query_args = ['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => 9, 'orderby' => 'date', 'order' => 'DESC'];
if ($selected) {
    $query_args['post__in'] = array_map(static function ($item) {
        return is_object($item) ? $item->ID : (int) $item;
    }, $selected);
    $query_args['orderby'] = 'post__in';
}
$reviews = get_posts($query_args);
if (!$reviews) {
    return;
}

$ratings = [];
foreach (get_posts(['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids']) as $review_id) {
    $rating = (int) round((float) get_field('rating', $review_id));
    if ($rating >= 1 && $rating <= 5) {
        $ratings[] = $rating;
    }
}
$total   = count($ratings);
$average = $total ? array_sum($ratings) / $total : 0;
$stars   = static function ($value) {
    return str_repeat('&#9733;', (int) round($value)) . '<span class="text-border">' . str_repeat('&#9733;', 5 - (int) round($value)) . '</span>';
};
$row_id = 'iva-reviews-' . wp_unique_id();
?>
<section class="iva-section iva-reviews overflow-hidden bg-surface">
    <div class="container-site">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div>
                <?php ivermectin_section_head('', $heading, '', 'light', 'left', '!mb-0'); ?>
                <?php if ($total) : ?>
                    <div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-2" data-iva-score>
                        <span class="font-heading text-5xl font-extrabold leading-none text-primary" data-iva-count="<?= esc_attr(number_format($average, 1)) ?>"><?= esc_html(number_format($average, 1)) ?></span>
                        <span>
                            <span class="iva-stars block text-xl leading-none" aria-hidden="true"><?= $stars($average) ?></span>
                            <?php if ($labels['score_text'] !== '') : ?><span class="mt-1 block text-[0.9375rem] text-muted-foreground"><?= esc_html(str_replace('{count}', (string) $total, $labels['score_text'])) ?></span><?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex shrink-0 gap-2">
                <button type="button" class="iva-arrow" data-iva-scroll="<?= esc_attr($row_id) ?>" data-dir="-1" aria-label="<?= esc_attr($labels['previous_label']) ?>"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
                <button type="button" class="iva-arrow" data-iva-scroll="<?= esc_attr($row_id) ?>" data-dir="1" aria-label="<?= esc_attr($labels['next_label']) ?>"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
            </div>
        </div>

        <div class="iva-review-track mt-12" id="<?= esc_attr($row_id) ?>" data-iva-stagger>
            <?php foreach ($reviews as $review) :
                $rating     = (int) get_field('rating', $review->ID);
                $name       = trim((string) get_field('name', $review->ID));
                $verified   = (bool) get_field('verified_buyer', $review->ID);
                $product_id = (int) get_field('linked_product', $review->ID);
                ?>
                <figure class="iva-review-card">
                    <?php if ($rating >= 1) : ?><span class="iva-stars text-lg leading-none" aria-label="<?= esc_attr(str_replace('{rating}', (string) $rating, $labels['rating_label'])) ?>"><?= $stars($rating) ?></span><?php endif; ?>
                    <?php if ($review->post_title !== '') : ?><p class="mt-5 font-heading text-xl font-bold leading-snug text-primary"><?= esc_html($review->post_title) ?></p><?php endif; ?>
                    <blockquote class="mt-3 line-clamp-6 text-[1.0625rem] leading-relaxed text-muted-foreground"><?= esc_html(wp_strip_all_tags($review->post_content)) ?></blockquote>
                    <figcaption class="mt-auto flex items-end justify-between gap-3 pt-8">
                        <span class="min-w-0">
                            <?php if ($name !== '') : ?><span class="block truncate font-semibold text-primary"><?= esc_html($name) ?></span><?php endif; ?>
                            <span class="block text-sm text-muted-foreground"><?= esc_html(get_the_date('', $review)) ?><?= $verified && $labels['verified_label'] !== '' ? ' | ' . esc_html($labels['verified_label']) : '' ?></span>
                        </span>
                        <?php if ($product_id && get_post_status($product_id) === 'publish') : ?>
                            <a href="<?= esc_url(get_permalink($product_id)) ?>" class="shrink-0 rounded-full bg-primary-soft px-3 py-1 text-xs font-bold text-primary hover:bg-primary hover:text-white"><?= esc_html(get_the_title($product_id)) ?></a>
                        <?php endif; ?>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>
