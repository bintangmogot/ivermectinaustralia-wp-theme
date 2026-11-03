<?php
/**
 * Product card used in loops: the same card as the homepage (photo, pack sizes, live price, Add to Cart).
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

if (empty($product) || !$product->is_visible()) {
    return;
}

get_template_part('template-parts/product-row-card', null, ['product' => $product]);
