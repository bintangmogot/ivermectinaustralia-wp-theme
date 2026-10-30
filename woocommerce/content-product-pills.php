<?php
/**
 * Popular Products card: quantity buttons in two columns, the price follows the selected button.
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if (empty($product) || !$product->is_visible()) {
    return;
}

$product_id = $product->get_id();
$title      = $product->get_title();
$link       = $product->get_permalink();
$image      = wp_get_attachment_image_url($product->get_image_id(), 'medium_large') ?: wc_placeholder_img_src();
$in_stock   = $product->is_in_stock();
$strength   = function_exists('ivermectin_get_product_strength') ? ivermectin_get_product_strength($product) : '';
$tab_label  = trim((string) get_field('product_badge', $product_id));
if ($tab_label === '' && $product->is_featured()) {
    $tab_label = ivermectin_get_product_presentation_option('best_seller_label');
}
$qty_heading = ivermectin_get_product_presentation_option('quantity_heading');
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
?>
<article class="lcard group h-full" data-iva-qcard>
    <?php if ($tab_label !== '') : ?><span class="lcard-tab"><?= esc_html($tab_label) ?></span><?php endif; ?>

    <a href="<?= esc_url($link) ?>" class="relative mx-4 mt-3 block aspect-[4/3] overflow-hidden rounded-lg border border-border bg-white p-3">
        <img src="<?= esc_url($image) ?>" alt="<?= esc_attr($title) ?>" class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105" loading="lazy">
        <?php if ($strength !== '') : ?><span class="absolute right-2 top-2 rounded-md border border-border bg-white px-2 py-0.5 text-xs font-bold text-primary"><?= esc_html($strength) ?></span><?php endif; ?>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <a href="<?= esc_url($link) ?>" class="mb-3 line-clamp-2 font-heading text-lg font-bold leading-snug text-foreground hover:text-primary"><?= esc_html($title) ?></a>

        <?php if ($product->is_type('variable')) : ?>
            <?php if ($qty_heading !== '') : ?><p class="mb-2 text-sm font-bold text-foreground"><?= esc_html($qty_heading) ?></p><?php endif; ?>
            <?php get_template_part('template-parts/quantity-picker', null, ['product' => $product]); ?>
        <?php endif; ?>

        <div class="mt-4 flex items-center justify-between gap-3">
            <span class="font-heading text-2xl font-extrabold text-primary" data-iva-price><?= wp_kses_post($first_price) ?></span>
            <?php if ($in_stock) : ?>
                <a href="<?= esc_url($link) ?>" class="btn-cta"><?= esc_html__('View Details', 'ivermectin-australia') ?></a>
            <?php else : ?>
                <span class="text-sm font-bold uppercase text-destructive"><?= esc_html__('Out of Stock', 'ivermectin-australia') ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
