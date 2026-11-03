<?php
/**
 * Quantity buttons for a variable product. Clicking one updates the price shown in the same [data-iva-qcard].
 * Args: product (WC_Product). Buttons are rendered as "N Tabs".
 * Each button also carries the add-to-cart link and the price per tablet for that pack, for cards that show them.
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
        $cart_url = add_query_arg(array_merge(
            ['add-to-cart' => $product->get_id(), 'variation_id' => $variation['variation_id']],
            array_map('rawurlencode', array_filter($variation['attributes']))
        ), wc_get_cart_url());
        $tab_count = preg_match('/\d+/', $label, $count_match) ? (int) $count_match[0] : 0;
        $per_tab   = $tab_count > 0 ? wc_price($variation['display_price'] / $tab_count, ['decimals' => 2]) : '';
        ?>
        <button type="button" class="iva-qbtn" aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>" data-iva-amount="<?= esc_attr(wc_price($variation['display_price'])) ?>" data-iva-cart-url="<?= esc_url($cart_url) ?>" data-iva-unit="<?= esc_attr($per_tab) ?>"><?= esc_html($label) ?></button>
    <?php endforeach; ?>
</div>
