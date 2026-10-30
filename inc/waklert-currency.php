<?php
/**
 * Australian dollar currency symbol & Side Cart Price Controls.
 *
 * 1. Display currency strictly as $ without the "A" prefix across the site and side cart.
 * 2. Set Side Cart checkout button price to match the product subtotal (excluding shipping/insurance).
 */

defined('ABSPATH') || exit;

// 1. Ensure WooCommerce currency symbol is strictly '$' without 'A'
add_filter('woocommerce_currency_symbol', function ($currency_symbol, $currency) {
    return '$';
}, 999, 2);

add_filter('formatted_woocommerce_price', function ($formatted_price, $price, $decimals, $decimal_separator, $thousand_separator, $original_price) {
    return str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $formatted_price);
}, 999, 6);

add_filter('woocommerce_price_format', function ($format, $currency_pos) {
    return '%1$s%2$s';
}, 999, 2);

add_filter('woocommerce_cart_item_price', function($price, $cart_item, $cart_item_key) {
    return str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $price);
}, 999, 3);

add_filter('woocommerce_cart_item_subtotal', function($subtotal, $cart_item, $cart_item_key) {
    return str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $subtotal);
}, 999, 3);

add_filter('woocommerce_cart_subtotal', function($cart_subtotal, $compound, $cart) {
    return str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $cart_subtotal);
}, 999, 3);

add_filter('woocommerce_cart_total', function($cart_total) {
    return str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $cart_total);
}, 999, 1);

// 2. Side Cart: Set Checkout button price to match product subtotal (not order total) and strip 'A'
add_filter('xoo_wsc_footer_buttons_args', 'waklert_side_cart_checkout_button_subtotal', 20);
function waklert_side_cart_checkout_button_subtotal($args) {
    if (isset($args['buttons']['checkout']) && function_exists('WC') && WC()->cart && !WC()->cart->is_empty()) {
        // Extract button text prefix (defaults to 'Checkout')
        $chk_text = 'Checkout';
        if (class_exists('Xoo_Wsc_Template_Args') && !empty(Xoo_Wsc_Template_Args::$gl['sct-ft-chkbtn'])) {
            $chk_text = trim(Xoo_Wsc_Template_Args::$gl['sct-ft-chkbtn']);
        }
        $chk_text = rtrim($chk_text, '- ');

        $show_total = true;
        if (class_exists('Xoo_Wsc_Template_Args') && isset(Xoo_Wsc_Template_Args::$gl['scf-chkbtntotal-en'])) {
            $show_total = (Xoo_Wsc_Template_Args::$gl['scf-chkbtntotal-en'] === 'yes');
        }

        if ($show_total) {
            // Use cart subtotal (product price total) instead of order total
            $subtotal = WC()->cart->get_cart_subtotal();
            // Ensure no 'A' prefix appears in the price
            $subtotal = str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $subtotal);
            // Note: side-cart-woocommerce adds ' - ' via .amount:before CSS, so we simply concatenate
            $args['buttons']['checkout']['label'] = $chk_text . $subtotal;
        } else {
            $args['buttons']['checkout']['label'] = $chk_text;
        }
    }
    return $args;
}

// 3. Clean any 'A' prefix from Side Cart totals display
add_filter('xoo_wsc_footer_totals_args', 'waklert_side_cart_clean_currency_totals', 20);
function waklert_side_cart_clean_currency_totals($args) {
    if (!empty($args['totals']) && is_array($args['totals'])) {
        foreach ($args['totals'] as $k => $total) {
            if (isset($total['value'])) {
                $args['totals'][$k]['value'] = str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $total['value']);
            }
        }
    }
    return $args;
}

// 4. Clean any 'A' prefix from Side Cart product items
add_filter('xoo_wsc_product_args', 'waklert_side_cart_clean_product_args', 20, 4);
function waklert_side_cart_clean_product_args($args, $_product, $cart_item, $cart_item_key) {
    if (isset($args['product_price'])) {
        $args['product_price'] = str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $args['product_price']);
    }
    if (isset($args['product_subtotal'])) {
        $args['product_subtotal'] = str_replace(array('A$', 'A&#36;', 'A&dollar;'), '$', $args['product_subtotal']);
    }
    return $args;
}

// 5. Client-side JS safety net to remove any stray 'A$' in the Side Cart drawer
add_action('wp_footer', function() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function cleanSideCartCurrency() {
            var container = document.querySelector('.xoo-wsc-container');
            if (!container) return;
            var walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null, false);
            var node;
            while ((node = walker.nextNode())) {
                if (node.nodeValue && node.nodeValue.indexOf('A$') !== -1) {
                    node.nodeValue = node.nodeValue.replace(/A\$/g, '$');
                }
            }
        }
        cleanSideCartCurrency();
        if (window.jQuery) {
            jQuery(document.body).on('wc_fragments_refreshed xoo_wsc_cart_updated added_to_cart', function() {
                setTimeout(cleanSideCartCurrency, 10);
            });
        }
        var container = document.querySelector('.xoo-wsc-container');
        if (container && window.MutationObserver) {
            var observer = new MutationObserver(cleanSideCartCurrency);
            observer.observe(container, { childList: true, subtree: true });
        }
    });
    </script>
    <?php
}, 999);
