<?php
/**
 * Module: reviews_carousel. Sub fields: title, reviews (relationship to the Reviews post type).
 * Selected reviews are shown, otherwise the latest published reviews. The score and count are
 * calculated from real review posts. With no reviews the module renders nothing.
 */
$heading  = trim((string) get_sub_field('title'));
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

$all_ids = get_posts(['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids']);
$ratings = [];
foreach ($all_ids as $review_id) {
    $rating = (float) get_field('rating', $review_id);
    if ($rating >= 1 && $rating <= 5) {
        $ratings[] = $rating;
    }
}
$average = $ratings ? array_sum($ratings) / count($ratings) : 0;
$stars   = static function ($value) {
    return str_repeat('&#9733;', (int) round($value)) . '<span class="text-border">' . str_repeat('&#9733;', 5 - (int) round($value)) . '</span>';
};
?>
<section class="py-8 md:py-10">
    <div class="container-site">
        <?php if ($heading !== '') : ?><h2 class="mb-5 font-heading text-2xl font-extrabold text-primary md:text-3xl"><?= esc_html($heading) ?></h2><?php endif; ?>
        <div class="grid gap-4 lg:grid-cols-[17rem_1fr]">
            <?php if ($ratings) : ?>
                <div class="rounded-xl bg-primary p-6 text-white">
                    <p class="font-heading text-5xl font-extrabold leading-none"><?= esc_html(number_format($average, 1)) ?></p>
                    <p class="iva-stars mt-2 text-lg" aria-hidden="true"><?= $stars($average) ?></p>
                    <p class="mt-2 text-sm text-white/80"><?= esc_html(sprintf(_n('%d review', '%d reviews', count($ratings), 'ivermectin-australia'), count($ratings))) ?></p>
                </div>
            <?php endif; ?>
            <div class="iva-scroll-row <?= $ratings ? '' : 'lg:col-span-2' ?>">
                <?php foreach ($reviews as $review) :
                    $rating     = (int) get_field('rating', $review->ID);
                    $name       = trim((string) get_field('name', $review->ID));
                    $verified   = (bool) get_field('verified_buyer', $review->ID);
                    $product_id = (int) get_field('linked_product', $review->ID);
                    ?>
                    <article class="lcard iva-scroll-item">
                        <div class="flex-1 p-5">
                            <div class="flex items-center justify-between gap-2">
                                <?php if ($rating >= 1) : ?><span class="iva-stars" aria-label="<?= esc_attr(sprintf(__('%d out of 5', 'ivermectin-australia'), $rating)) ?>"><?= $stars($rating) ?></span><?php endif; ?>
                                <?php if ($verified) : ?><span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700"><?= ivermectin_icon('verified_reviews', 'h-4 w-4') ?><?= esc_html__('Verified buyer', 'ivermectin-australia') ?></span><?php endif; ?>
                            </div>
                            <?php if ($review->post_title !== '') : ?><h3 class="mt-3 font-heading text-base font-bold text-foreground"><?= esc_html($review->post_title) ?></h3><?php endif; ?>
                            <div class="mt-2 text-sm leading-relaxed text-foreground"><?= wp_kses_post(wpautop($review->post_content)) ?></div>
                            <?php if ($name !== '') : ?><p class="mt-3 text-sm font-bold text-foreground"><?= esc_html($name) ?></p><?php endif; ?>
                        </div>
                        <?php if ($product_id && get_post_status($product_id) === 'publish') : ?>
                            <div class="lcard-foot"><a href="<?= esc_url(get_permalink($product_id)) ?>" class="font-semibold text-link hover:underline"><?= esc_html(get_the_title($product_id)) ?></a></div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
