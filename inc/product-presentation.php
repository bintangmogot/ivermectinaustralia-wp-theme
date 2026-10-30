<?php
/**
 * Product presentation helpers backed by WooCommerce and editable review data.
 */

if (!function_exists('ivermectin_get_product_attribute_by_label')) {
    function ivermectin_get_product_attribute_by_label($product, $pattern) {
        if (!is_object($product) || !method_exists($product, 'get_attributes')) {
            return '';
        }

        foreach ($product->get_attributes() as $attribute) {
            if (!is_object($attribute) || !method_exists($attribute, 'get_name')) {
                continue;
            }

            $name = $attribute->get_name();
            $label = wc_attribute_label($name, $product);
            if (!preg_match($pattern, $label . ' ' . $name)) {
                continue;
            }

            $value = trim((string) $product->get_attribute($name));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }
}

if (!function_exists('ivermectin_get_product_strength')) {
    function ivermectin_get_product_strength($product) {
        $strength = ivermectin_get_product_attribute_by_label($product, '/strength|dosage/i');
        if ($strength !== '') {
            return $strength;
        }

        if (is_object($product) && preg_match('/\b\d+(?:\.\d+)?\s?mg\b/i', $product->get_name(), $matches)) {
            return preg_replace('/\s+/', ' ', $matches[0]);
        }

        return '';
    }
}

if (!function_exists('ivermectin_get_product_pack_count')) {
    function ivermectin_get_product_pack_count($product, $variation = null) {
        if (!is_object($product)) {
            return 0;
        }

        $attributes = $variation && method_exists($variation, 'get_variation_attributes')
            ? $variation->get_variation_attributes()
            : $product->get_attributes();

        foreach ($attributes as $key => $attribute) {
            if (is_object($attribute) && method_exists($attribute, 'get_name')) {
                $name = $attribute->get_name();
                $raw_value = $product->get_attribute($name);
            } else {
                $name = preg_replace('/^attribute_/', '', (string) $key);
                $raw_value = is_scalar($attribute) ? (string) $attribute : '';
                if ($variation && method_exists($variation, 'get_attribute')) {
                    $raw_value = $variation->get_attribute($name) ?: $raw_value;
                } else {
                    $raw_value = $product->get_attribute($name) ?: $raw_value;
                }
            }

            $label = wc_attribute_label($name, $product);
            if (!preg_match('/quantity|pack|size|tablet|\btab\b/i', $label . ' ' . $name)) {
                continue;
            }

            if (preg_match('/\d[\d,]*(?:\.\d+)?/', wp_strip_all_tags((string) $raw_value), $matches)) {
                return (float) str_replace(',', '', $matches[0]);
            }
        }

        return 0;
    }
}

if (!function_exists('ivermectin_get_product_unit_price')) {
    function ivermectin_get_product_unit_price($product) {
        if (!is_object($product) || !method_exists($product, 'get_price')) {
            return null;
        }

        $unit_prices = [];
        if ($product->is_type('variable')) {
            foreach ($product->get_children() as $variation_id) {
                $variation = wc_get_product($variation_id);
                if (!$variation || !$variation->variation_is_visible() || $variation->get_price() === '') {
                    continue;
                }

                $pack_count = ivermectin_get_product_pack_count($product, $variation);
                if ($pack_count > 0) {
                    $unit_prices[] = (float) $variation->get_price() / $pack_count;
                }
            }
        } else {
            $pack_count = ivermectin_get_product_pack_count($product);
            if ($pack_count > 0 && $product->get_price() !== '') {
                $unit_prices[] = (float) $product->get_price() / $pack_count;
            }
        }

        return $unit_prices ? min($unit_prices) : null;
    }
}

if (!function_exists('ivermectin_get_product_card_price_html')) {
    /**
     * Format catalogue card prices as whole dollars without changing the
     * store's configured precision for carts, checkout, or product details.
     */
    function ivermectin_get_product_card_price_html($product) {
        if (!is_object($product) || !method_exists($product, 'get_price_html')) {
            return '';
        }

        $whole_dollar_precision = static function () {
            return 0;
        };

        add_filter('wc_get_price_decimals', $whole_dollar_precision, PHP_INT_MAX);
        try {
            return $product->get_price_html();
        } finally {
            remove_filter('wc_get_price_decimals', $whole_dollar_precision, PHP_INT_MAX);
        }
    }
}

if (!function_exists('ivermectin_format_product_unit_price')) {
    function ivermectin_format_product_unit_price($amount) {
        $currency = get_woocommerce_currency();
        $symbol = '$';

        return $symbol . number_format(
            (float) $amount,
            2,
            wc_get_price_decimal_separator(),
            wc_get_price_thousand_separator()
        );
    }
}

if (!function_exists('ivermectin_get_product_presentation_option')) {
    /** Value of a Product Page Presentation setting. The second argument is kept for older callers and ignored. */
    function ivermectin_get_product_presentation_option($field_name, $unused_default = '') {
        return function_exists('get_field') ? trim((string) get_field($field_name, 'option')) : '';
    }
}

if (!function_exists('ivermectin_get_product_review_summary')) {
    function ivermectin_get_product_review_summary($product_id) {
        static $cache = [];
        $product_id = absint($product_id);
        if (isset($cache[$product_id])) {
            return $cache[$product_id];
        }

        $reviews = get_posts([
            'post_type' => 'reviews',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => [[
                'key' => 'linked_product',
                'value' => $product_id,
                'compare' => '=',
            ]],
        ]);

        $ratings = [];
        foreach ($reviews as $review) {
            $rating = function_exists('get_field')
                ? get_field('rating', $review->ID)
                : get_post_meta($review->ID, 'rating', true);
            if (is_numeric($rating) && (float) $rating >= 1 && (float) $rating <= 5) {
                $ratings[] = (float) $rating;
            }
        }

        $cache[$product_id] = [
            'reviews' => $reviews,
            'review_count' => count($reviews),
            'rating_count' => count($ratings),
            'average_rating' => $ratings ? round(array_sum($ratings) / count($ratings), 1) : null,
        ];

        return $cache[$product_id];
    }
}

if (!function_exists('ivermectin_sort_product_variations')) {
    /**
     * Sort available product variations in logical ascending order:
     * 1. Pack count (tablet / quantity count) ascending: 30, 50, 60, 100, 120, 200, 240, 300, 400, 480, 500, 900, 1000.
     * 2. Total price ascending if pack count is equal or zero.
     * 3. Variation ID ascending as fallback.
     */
    function ivermectin_sort_product_variations(&$variations, $product = null) {
        if (!is_array($variations) || count($variations) <= 1) {
            return;
        }

        $items = [];
        foreach ($variations as $var) {
            $var_id = (int) ($var['variation_id'] ?? 0);
            $var_product = $var_id ? wc_get_product($var_id) : null;
            $pack_count = 0;
            if ($product && $var_product && function_exists('ivermectin_get_product_pack_count')) {
                $pack_count = (int) ivermectin_get_product_pack_count($product, $var_product);
            }
            if (!$pack_count && !empty($var['attributes'])) {
                foreach ($var['attributes'] as $attr_val) {
                    if (preg_match('/\b(\d+)\b/', (string) $attr_val, $m)) {
                        $pack_count = (int) $m[1];
                        break;
                    }
                }
            }

            $price = isset($var['display_price']) && is_numeric($var['display_price'])
                ? (float) $var['display_price']
                : 0.0;

            $items[] = [
                'data'  => $var,
                'pack'  => $pack_count,
                'price' => $price,
                'id'    => $var_id,
            ];
        }

        usort($items, static function ($a, $b) {
            if ($a['pack'] > 0 && $b['pack'] > 0 && $a['pack'] !== $b['pack']) {
                return $a['pack'] <=> $b['pack'];
            }
            if ($a['price'] !== $b['price']) {
                return $a['price'] <=> $b['price'];
            }
            return $a['id'] <=> $b['id'];
        });

        $variations = array_column($items, 'data');
    }
}

