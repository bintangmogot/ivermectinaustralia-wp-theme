<?php
/**
 * Quantity buttons for a variable product. Clicking one updates the price shown in the same [data-iva-qcard].
 * Args: product (WC_Product). Buttons are rendered as "N Tabs".
 */
defined('ABSPATH') || exit;

$product = $args['product'] ?? null;
if (!is_a($product, 'WC_Product') || !$product->is_type('variable')) {
    return;
}

$variations = $product->get_available_variations();
if (function_exists('ivermectin_sort_product_variations')) {
    ivermectin_sort_product_variations($variations, $product);
}
if (!$variations) {
    return;
}
?>
<div class="grid grid-cols-2 gap-2" role="group">
    <?php foreach ($variations as $index => $variation) :
        $label = '';
        foreach ($variation['attributes'] as $value) {
            if ($value) {
                $label = $value;
                break;
            }
        }
        if ($label === '') {
            $parts = explode('-', get_the_title($variation['variation_id']));
            $label = trim(end($parts));
        }
        $label = trim(str_ireplace(['-tabs', '-'], [' Tabs', ' '], $label));
        $label = trim(str_ireplace('tablets', 'Tabs', $label));
        if ($label !== '' && stripos($label, 'tab') === false) {
            $label .= ' Tabs';
        }
        ?>
        <button type="button" class="iva-qbtn" aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>" data-iva-amount="<?= esc_attr(wc_price($variation['display_price'])) ?>"><?= esc_html($label) ?></button>
    <?php endforeach; ?>
</div>
