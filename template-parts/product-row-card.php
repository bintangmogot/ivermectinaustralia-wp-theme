<?php
/**
 * Homepage product card: photo with the strength capsule, pack-size buttons, live price and price per tablet,
 * then Add to Cart for the chosen pack and a link to the product page.
 * $args['product'] is a WC_Product.
 */

defined('ABSPATH') || exit;

$product = $args['product'] ?? null;
if (!is_a($product, 'WC_Product') || !$product->is_visible()) {
    return;
}

$link        = $product->get_permalink();
$title       = $product->get_name();
$image_id    = $product->get_image_id();
$strength    = function_exists('ivermectin_get_product_strength') ? ivermectin_get_product_strength($product) : '';
$option      = static fn(string $name): string => function_exists('ivermectin_get_product_presentation_option') ? ivermectin_get_product_presentation_option($name) : '';
$qty_heading = $option('quantity_heading');
$labels      = [
    'in_stock'     => $option('in_stock_label'),
    'out_of_stock' => $option('out_of_stock_label'),
    'per_tab'      => $option('per_tab_label'),
    'add_to_cart'  => $option('add_to_cart_label'),
    'details'      => $option('details_label'),
];
$tab_label   = trim((string) get_field('product_badge', $product->get_id()));
if ($tab_label === '' && $product->is_featured() && function_exists('ivermectin_get_product_presentation_option')) {
    $tab_label = $option('best_seller_label');
}

$is_variable = $product->is_type('variable');
$first_price = wc_price((float) wc_get_price_to_display($product));
$cart_url    = $product->is_purchasable() && !$is_variable ? $product->add_to_cart_url() : '';
$per_tab     = '';
if ($is_variable) {
    $variations = $product->get_available_variations();
    if (function_exists('ivermectin_sort_product_variations')) {
        ivermectin_sort_product_variations($variations, $product);
    }
    if ($variations) {
        $first       = $variations[0];
        $first_price = wc_price($first['display_price']);
        $cart_url    = add_query_arg(array_merge(
            ['add-to-cart' => $product->get_id(), 'variation_id' => $first['variation_id']],
            array_map('rawurlencode', array_filter($first['attributes']))
        ), wc_get_cart_url());
        $first_label = implode(' ', array_filter($first['attributes']));
        if (preg_match('/\d+/', $first_label, $count_match) && (int) $count_match[0] > 0) {
            $per_tab = wc_price($first['display_price'] / (int) $count_match[0], ['decimals' => 2]);
        }
    }
}

$dose = $unit = '';
if ($strength !== '' && preg_match('/^\s*([\d.]+)\s*([a-z]+)\s*$/i', $strength, $parts)) {
    $dose = $parts[1];
    $unit = $parts[2];
}
?>
<article class="iva-prow group" data-iva-qcard>
    <a href="<?= esc_url($link) ?>" class="iva-prow-media" tabindex="-1" aria-hidden="true">
        <?php if ($tab_label !== '') : ?><span class="iva-prow-flag"><?= esc_html($tab_label) ?></span><?php endif; ?>
        <?php if ($image_id) : ?>
            <?= wp_get_attachment_image($image_id, 'woocommerce_thumbnail', false, ['class' => 'h-full w-full object-contain transition-transform duration-300 group-hover:scale-105', 'alt' => '']) ?>
        <?php else : ?>
            <img src="<?= esc_url(wc_placeholder_img_src()) ?>" alt="" class="h-full w-full object-contain">
        <?php endif; ?>
        <?php if ($dose !== '') : ?>
            <span class="iva-capsule iva-capsule-float" aria-label="<?= esc_attr($strength) ?>"><span class="iva-capsule-dose"><?= esc_html($dose) ?></span><span class="iva-capsule-unit"><?= esc_html($unit) ?></span></span>
        <?php endif; ?>
    </a>

    <div class="iva-prow-body flex min-w-0 flex-1 flex-col">
        <div class="iva-prow-head flex items-start justify-between gap-3">
            <h3 class="font-heading text-[0.9375rem] font-bold leading-snug text-primary sm:text-lg md:text-xl"><a href="<?= esc_url($link) ?>" class="hover:text-link"><?= esc_html($title) ?></a></h3>
            <?php if ($product->is_in_stock() && $labels['in_stock'] !== '') : ?><span class="mt-1 inline-flex shrink-0 items-center gap-1.5 text-xs font-bold text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500" aria-hidden="true"></span><?= esc_html($labels['in_stock']) ?></span><?php endif; ?>
        </div>

        <?php if ($is_variable) : ?>
            <div class="mt-3">
                <?php if ($qty_heading !== '') : ?><p class="mb-2 text-xs font-bold text-muted-foreground"><?= esc_html($qty_heading) ?></p><?php endif; ?>
                <?php get_template_part('template-parts/quantity-picker', null, ['product' => $product]); ?>
            </div>
        <?php endif; ?>

        <div class="iva-prow-price mt-auto flex items-end justify-between gap-3 border-t border-dashed border-border pt-3">
            <span class="font-heading text-[1.375rem] font-extrabold leading-none text-primary sm:text-[1.75rem]" data-iva-price><?= wp_kses_post($first_price) ?></span>
            <?php if ($per_tab !== '') : ?><span class="text-right text-xs text-muted-foreground"><span class="font-bold text-foreground" data-iva-unit-out><?= wp_kses_post($per_tab) ?></span> <?= esc_html($labels['per_tab']) ?></span><?php endif; ?>
        </div>

        <div class="mt-3 grid gap-2 md:grid-cols-[1fr_auto]">
            <?php if ($product->is_in_stock() && $cart_url !== '') : ?>
                <a href="<?= esc_url($cart_url) ?>" class="btn-navy rounded-full py-2.5 text-sm" data-iva-cart rel="nofollow">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>
                    <?= esc_html($labels['add_to_cart']) ?>
                </a>
            <?php else : ?>
                <span class="grid place-items-center rounded-full bg-destructive-soft py-2.5 text-sm font-bold text-destructive"><?= esc_html($labels['out_of_stock']) ?></span>
            <?php endif; ?>
            <a href="<?= esc_url($link) ?>" target="_blank" rel="noopener noreferrer" class="hidden items-center rounded-full border-2 border-primary px-4 py-2 font-heading md:inline-flex text-sm font-bold text-primary transition-colors hover:bg-primary hover:text-white"><?= esc_html($labels['details']) ?></a>
        </div>
    </div>
</article>
