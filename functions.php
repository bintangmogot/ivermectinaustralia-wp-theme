<?php
/**
 * Ivermectin Australia - Theme Functions
 *
 * This file only registers behaviour. It never writes to the database on load:
 * no option updates, no post or meta rewrites. Existing site content is left as is.
 */

define('IVERMECTIN_THEME_VERSION', '1.0.0');
define('IVERMECTIN_THEME_DIR', get_stylesheet_directory());
define('IVERMECTIN_THEME_URI', get_stylesheet_directory_uri());

/** Subtle grid overlay used on navy hero and band sections. */
function ivermectin_output_pattern_rings($extra_class = '') {
    $class = $extra_class ? ' ' . trim(esc_attr($extra_class)) : '';
    echo '<div aria-hidden="true" class="iva-grid-bg pointer-events-none absolute inset-0 z-0' . $class . '"></div>';
}

// 1. Theme setup (menus, supports, image sizes, logo helpers)
require_once IVERMECTIN_THEME_DIR . '/inc/theme-setup.php';
require_once IVERMECTIN_THEME_DIR . '/inc/nav-walkers.php';
require_once IVERMECTIN_THEME_DIR . '/inc/icon-library.php';
require_once IVERMECTIN_THEME_DIR . '/inc/section-head.php';
require_once IVERMECTIN_THEME_DIR . '/inc/rich-article.php';

// 2. Assets
require_once IVERMECTIN_THEME_DIR . '/inc/enqueue.php';

// 3. ACF Theme Settings (options page)
require_once IVERMECTIN_THEME_DIR . '/inc/theme-options.php';

// 4. Shortcodes
require_once IVERMECTIN_THEME_DIR . '/inc/shortcodes.php';

// 5. WooCommerce
require_once IVERMECTIN_THEME_DIR . '/inc/woocommerce.php';
require_once IVERMECTIN_THEME_DIR . '/inc/currency.php';

// 6. Post types and delivery areas
require_once IVERMECTIN_THEME_DIR . '/inc/post-types.php';
require_once IVERMECTIN_THEME_DIR . '/inc/australia-delivery.php';

// 7. Product presentation and reviews
require_once IVERMECTIN_THEME_DIR . '/inc/product-presentation.php';
require_once IVERMECTIN_THEME_DIR . '/inc/ajax-reviews.php';

// 8. Existing site content (old modules, old options, old product fields)
require_once IVERMECTIN_THEME_DIR . '/inc/legacy-content.php';
require_once IVERMECTIN_THEME_DIR . '/inc/content-seed.php';

// ACF fallbacks so templates never fatal if ACF is deactivated.
if (!function_exists('get_field')) {
    function get_field($selector, $post_id = false, $format_value = true) { return false; }
}
if (!function_exists('get_sub_field')) {
    function get_sub_field($selector, $format_value = true) { return false; }
}
if (!function_exists('have_rows')) {
    function have_rows($selector, $post_id = false) { return false; }
}
if (!function_exists('the_row')) {
    function the_row() { return false; }
}

// SVG uploads for administrators only.
add_filter('upload_mimes', function ($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

// Gravity Forms: contact form submit button markup.
add_filter('gform_submit_button', function ($button, $form) {
    if ((int) $form['id'] !== 1) {
        return $button;
    }
    return sprintf(
        '<button type="submit" id="gform_submit_button_%d" class="button gform_button inline-flex items-center justify-center gap-2 w-full md:w-auto"><span>%s</span></button>',
        (int) $form['id'],
        esc_html__('Send Message', 'ivermectin-australia')
    );
}, 10, 2);

// Reviews: extra fields. Registered only when the site has no existing group with a "rating" field.
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    $fields = array(
        array(
            'key'           => 'field_iva_review_linked_product',
            'label'         => 'Linked Product',
            'name'          => 'linked_product',
            'type'          => 'post_object',
            'instructions'  => 'Select a product if this is a product review. Leave empty for general reviews.',
            'post_type'     => array('product'),
            'allow_null'    => 1,
            'multiple'      => 0,
            'return_format' => 'id',
            'ui'            => 1,
        ),
        array(
            'key'           => 'field_iva_review_verified',
            'label'         => 'Verified Buyer',
            'name'          => 'verified_buyer',
            'type'          => 'true_false',
            'ui'            => 1,
            'default_value' => 0,
        ),
    );

    $has_rating_group = false;
    foreach (acf_get_field_groups(array('post_type' => 'reviews')) as $group) {
        if (!empty($group['local'])) {
            continue;
        }
        foreach ((array) acf_get_fields($group) as $existing) {
            if (isset($existing['name']) && $existing['name'] === 'rating') {
                $has_rating_group = true;
                break 2;
            }
        }
    }

    if (!$has_rating_group) {
        array_unshift(
            $fields,
            array(
                'key'           => 'field_iva_review_rating',
                'label'         => 'Rating',
                'name'          => 'rating',
                'type'          => 'select',
                'choices'       => array('5' => '5', '4' => '4', '3' => '3', '2' => '2', '1' => '1'),
                'default_value' => '5',
                'return_format' => 'value',
            ),
            array(
                'key'   => 'field_iva_review_name',
                'label' => 'Reviewer Name',
                'name'  => 'name',
                'type'  => 'text',
            )
        );
    }

    acf_add_local_field_group(array(
        'key'      => 'group_iva_review_details',
        'title'    => 'Review Details',
        'fields'   => $fields,
        'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'reviews'))),
        'position' => 'side',
        'active'   => true,
    ));
}, 30);

// Reviews: admin list column.
add_filter('manage_reviews_posts_columns', function ($columns) {
    $columns['linked_product'] = 'Linked Product';
    return $columns;
});
add_action('manage_reviews_posts_custom_column', function ($column, $post_id) {
    if ($column !== 'linked_product') {
        return;
    }
    $product_id = get_field('linked_product', $post_id);
    echo $product_id ? esc_html(get_the_title($product_id)) : '';
}, 10, 2);

/** Hide variation attributes (for example Pack Size) from the product specs table. */
add_filter('woocommerce_display_product_attributes', 'ivermectin_hide_variation_attributes_from_specs', 10, 2);
function ivermectin_hide_variation_attributes_from_specs($product_attributes, $product) {
    if (!is_object($product)) {
        return $product_attributes;
    }
    foreach ($product->get_attributes() as $attribute) {
        if (!$attribute->get_variation()) {
            continue;
        }
        $label = wc_attribute_label($attribute->get_name());
        foreach ($product_attributes as $key => $formatted_attribute) {
            if (isset($formatted_attribute['label']) && $formatted_attribute['label'] === $label) {
                unset($product_attributes[$key]);
            }
        }
    }
    return $product_attributes;
}

/** Remove the Additional Information tab when there is nothing but variation attributes. */
add_filter('woocommerce_product_tabs', 'ivermectin_remove_empty_additional_information_tab', 98);
function ivermectin_remove_empty_additional_information_tab($tabs) {
    global $product;
    if (!$product || !is_a($product, 'WC_Product')) {
        return $tabs;
    }
    foreach ($product->get_attributes() as $attribute) {
        if ($attribute->get_visible() && !$attribute->get_variation()) {
            return $tabs;
        }
    }
    unset($tabs['additional_information']);
    return $tabs;
}

// Remove the WooCommerce mobile app promotion line from email footers.
add_action('woocommerce_email', function ($email_class) {
    remove_action('woocommerce_email_footer', array($email_class, 'mobile_messaging'), 9);
});

/** First category name of a post. */
function ivermectin_get_post_category($post_id) {
    $categories = get_the_category($post_id);
    return !empty($categories) ? $categories[0]->name : '';
}
