<?php
/**
 * Module: hero_section.
 * Sub fields: location_text, heading, subtitle, description, hero_product, primary and secondary buttons,
 * stats (value, label), background_image. Empty fields render nothing.
 * Light hero: text on the left, the photo framed on the right, anchored right so every product stays in view, with the review badge and the mascot.
 * When a product is chosen the quick order card replaces the photo.
 */
$location    = trim((string) get_sub_field('location_text'));
$heading     = trim((string) get_sub_field('heading'));
$subtitle    = trim((string) get_sub_field('subtitle'));
$description = trim((string) get_sub_field('description'));
$primary_btn = trim((string) get_sub_field('primary_button_text'));
$primary_url = trim((string) get_sub_field('primary_button_link'));
$second_btn  = trim((string) get_sub_field('secondary_button_text'));
$second_url  = trim((string) get_sub_field('secondary_button_link'));
$stats       = get_sub_field('stats') ?: [];
$badge_text  = trim((string) get_sub_field('review_badge_text'));
$reviews_url = (string) get_sub_field('review_badge_link');
if ($reviews_url === '') {
    $reviews_page = get_page_by_path('our-reviews');
    $reviews_url = $reviews_page && $reviews_page->post_status === 'publish' ? get_permalink($reviews_page) : '';
}
$show_mascot = (bool) get_sub_field('show_mascot');
$product     = class_exists('WooCommerce') && get_sub_field('hero_product') ? wc_get_product((int) get_sub_field('hero_product')) : null;

$image_field = get_sub_field('background_image');
$image_id    = is_array($image_field) ? (int) ($image_field['ID'] ?? 0) : (int) $image_field;

if ($heading === '' && $description === '' && !$product) {
    return;
}

// Review score from real review posts, shown as a badge over the photo.
$review_ratings = [];
foreach (get_posts(['post_type' => 'reviews', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids']) as $review_id) {
    $rating = (float) get_field('rating', $review_id);
    if ($rating >= 1 && $rating <= 5) {
        $review_ratings[] = $rating;
    }
}
$review_count   = count($review_ratings);
$review_average = $review_count ? array_sum($review_ratings) / $review_count : 0;

$option      = static fn(string $name): string => function_exists('ivermectin_get_product_presentation_option') ? ivermectin_get_product_presentation_option($name) : '';
$qty_heading = $option('quantity_heading');
?>
<section class="iva-hero relative isolate overflow-hidden" data-testid="hero-section">
    <div class="container-site relative grid gap-10 pb-16 pt-10 md:pb-24 md:pt-16 lg:grid-cols-[1fr_1fr] lg:items-center lg:gap-12">
        <div class="max-w-xl">
            <?php if ($location !== '') : ?><p class="iva-pill-tag iva-pill-tag-icon" data-iva-hero><?= ivermectin_icon('australia_delivery', 'h-5 w-5') ?><?= esc_html($location) ?></p><?php endif; ?>
            <?php if ($heading !== '') : ?><h1 class="iva-display mt-6 text-primary" data-iva-hero><?= esc_html($heading) ?></h1><?php endif; ?>
            <?php if ($subtitle !== '') : ?><p data-iva-hero class="mt-4 font-heading text-[1.25rem] font-semibold leading-snug text-primary/75 md:text-[1.5rem]"><?= esc_html($subtitle) ?></p><?php endif; ?>
            <?php if ($description !== '') : ?><p data-iva-hero class="mt-5 text-[1.125rem] leading-relaxed text-muted-foreground"><?= esc_html($description) ?></p><?php endif; ?>

            <?php if (($primary_btn !== '' && $primary_url !== '') || ($second_btn !== '' && $second_url !== '')) : ?>
                <div data-iva-hero class="mt-8 flex flex-wrap gap-3">
                    <?php if ($primary_btn !== '' && $primary_url !== '') : ?><a href="<?= esc_url($primary_url) ?>" class="btn-cta iva-btn-lift rounded-full px-7 py-3.5 text-base"><?= esc_html($primary_btn) ?></a><?php endif; ?>
                    <?php if ($second_btn !== '' && $second_url !== '') : ?><a href="<?= esc_url($second_url) ?>" class="iva-btn-outline rounded-full px-7 py-3.5 text-base"><?= esc_html($second_btn) ?></a><?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($stats) : ?>
                <ul data-iva-hero class="iva-hero-stats mt-10">
                    <?php foreach ($stats as $stat) : if (empty($stat['value'])) { continue; } ?>
                        <li>
                            <span class="block font-heading text-lg font-extrabold leading-tight text-primary sm:text-xl"><?= esc_html($stat['value']) ?></span>
                            <span class="mt-0.5 block text-[0.75rem] leading-snug text-muted-foreground sm:text-[0.8125rem]"><?= esc_html($stat['label'] ?? '') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (!$product && $image_id) : ?>
            <div class="iva-hero-visual" data-iva-hero-visual>
                <div class="iva-hero-frame">
                    <?= wp_get_attachment_image($image_id, 'large', false, ['class' => 'w-full', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1024px) 560px, 100vw']) ?>
                </div>
                <?php if ($review_count > 0 && $badge_text !== '') : ?>
                    <<?= $reviews_url !== '' ? 'a href="' . esc_url($reviews_url) . '"' : 'div' ?> class="iva-float-badge">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-primary-soft"><?= ivermectin_icon('verified_reviews', 'h-7 w-7') ?></span>
                        <span>
                            <span class="iva-stars block text-sm leading-none" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                            <span class="mt-1 block font-heading text-sm font-bold text-primary"><?= esc_html(str_replace(['{score}', '{count}'], [number_format($review_average, 1), (string) $review_count], $badge_text)) ?></span>
                        </span>
                    </<?= $reviews_url !== '' ? 'a' : 'div' ?>>
                <?php endif; ?>
                <?php if ($show_mascot) { get_template_part('template-parts/mascot', null, ['pose' => 'deliver', 'class' => 'iva-hero-mascot']); } ?>
            </div>
        <?php endif; ?>

        <?php if ($product && $product->is_visible()) :
            $first_price = $product->get_price_html();
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                if (function_exists('ivermectin_sort_product_variations')) {
                    ivermectin_sort_product_variations($variations, $product);
                }
                if ($variations) {
                    $first_price = wc_price($variations[0]['display_price']);
                }
            }
            $strength = function_exists('ivermectin_get_product_strength') ? ivermectin_get_product_strength($product) : '';
            ?>
            <div class="overflow-hidden rounded-2xl border border-border bg-white text-foreground shadow-[0_30px_60px_-40px_rgb(1_44_112/0.5)] lg:ml-auto lg:w-full lg:max-w-md" data-iva-qcard>
                <div class="flex items-center justify-between gap-3 border-b border-border bg-primary-soft px-5 py-3 text-sm font-semibold text-primary">
                    <span><?= esc_html($product->get_name()) ?></span>
                    <span class="inline-flex items-center gap-1.5 <?= $product->is_in_stock() ? 'text-emerald-700' : 'text-destructive' ?>"><span class="h-2 w-2 rounded-full <?= $product->is_in_stock() ? 'bg-emerald-500' : 'bg-destructive' ?>" aria-hidden="true"></span><?= esc_html($product->is_in_stock() ? $option('in_stock_label') : $option('out_of_stock_label')) ?></span>
                </div>
                <div class="space-y-4 p-5">
                    <div class="flex items-center gap-4">
                        <?php if ($product->get_image_id()) : ?><span class="grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-lg border border-border bg-primary-softer"><?= wp_get_attachment_image($product->get_image_id(), 'thumbnail', false, ['class' => 'h-full w-full object-contain']) ?></span><?php endif; ?>
                        <?php if ($strength !== '') : ?><p class="font-heading text-3xl font-extrabold text-primary"><?= esc_html($strength) ?></p><?php endif; ?>
                    </div>
                    <?php if ($product->is_type('variable')) : ?>
                        <div>
                            <?php if ($qty_heading !== '') : ?><p class="mb-2 text-sm font-bold"><?= esc_html($qty_heading) ?></p><?php endif; ?>
                            <?php get_template_part('template-parts/quantity-picker', null, ['product' => $product]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-heading text-3xl font-extrabold text-primary" data-iva-price><?= wp_kses_post($first_price) ?></span>
                        <a href="<?= esc_url($product->get_permalink()) ?>" class="btn-navy"><?= esc_html($option('add_to_cart_label')) ?></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
