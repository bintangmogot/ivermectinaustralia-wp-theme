<?php
/**
 * WooCommerce customisations.
 * Display and template behaviour only. Store settings (payment, emails, coupons) stay in WooCommerce.
 */

if (!class_exists('WooCommerce')) {
    return;
}

// Classic cart and checkout. The theme overrides these templates.
add_filter('woocommerce_should_load_cart_checkout_blocks_frontend', '__return_false');
add_filter('wc_use_checkout_block', '__return_false');
add_filter('wc_use_cart_block', '__return_false');

// The single product template renders its own purchase panel.
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

add_filter('wc_get_product_category_list', '__return_empty_string');

add_filter('loop_shop_per_page', function () {
    return 16;
}, 20);

add_action('wp', 'ivermectin_remove_wc_elements');
function ivermectin_remove_wc_elements() {
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

// Show the PayID under the bank details in customer order emails.
add_action('woocommerce_email_before_order_table', function ($order, $sent_to_admin, $plain_text = false) {
    if (!$order || $sent_to_admin || $order->get_payment_method() !== 'bacs') {
        return;
    }
    $payid_number = function_exists('ivermectin_get_payid') ? ivermectin_get_payid() : '';
    if ($payid_number === '') {
        return;
    }
    if ($plain_text) {
        echo "\nPayID: " . $payid_number . "\n\n";
        return;
    }
    echo '<p class="bacs-payid-email" style="font-weight:700;font-size:16px;margin:16px 0 24px 0;">PayID: ' . esc_html($payid_number) . '</p>';
}, 11, 3);

require_once __DIR__ . '/cart-shipping-insurance.php';
