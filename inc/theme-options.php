<?php
/**
 * ACF Theme Options Pages and ACF JSON paths.
 * Nothing in this file writes to the database.
 */

if (function_exists('acf_add_options_page')) {
    add_action('acf/init', function () {
        acf_add_options_page(array(
            'page_title' => 'Theme Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug'  => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'icon_url'   => 'dashicons-admin-generic',
            'position'   => 30,
        ));

        // Options page used by the previous theme. Kept so its existing fields stay editable.
        acf_add_options_page(array(
            'page_title' => 'Legacy Site Options',
            'menu_title' => 'Legacy Options',
            'menu_slug'  => 'acf-options',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'icon_url'   => 'dashicons-archive',
            'position'   => 31,
        ));

        // Resolve the assigned WooCommerce page in each environment, rather than storing a local page ID in JSON.
        $shop_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : -1;
        if ($shop_page_id > 0) {
            acf_add_local_field_group([
                'key' => 'group_iva_shop_header',
                'title' => 'Shop Page Header',
                'fields' => [
                    [
                        'key' => 'field_iva_shop_heading',
                        'name' => 'shop_page_heading',
                        'label' => 'Shop H1 heading',
                        'type' => 'text',
                        'instructions' => 'Heading above the standard catalogue. Leave empty to use this page title. If Page Modules are enabled, edit the heading in its hero module instead.',
                    ],
                    [
                        'key' => 'field_iva_shop_intro',
                        'name' => 'shop_page_text',
                        'label' => 'Shop introductory text',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'instructions' => 'Optional text directly below the catalogue heading. Existing content modules remain below the products.',
                    ],
                ],
                'location' => [[['param' => 'page', 'operator' => '==', 'value' => (string) $shop_page_id]]],
            ]);
        }
    });
}

/**
 * Turn a phone number or URL into a WhatsApp chat URL. Returns '' when there is no usable number.
 */
function ivermectin_format_whatsapp_url($phone_or_url = null) {
    $val = trim((string) $phone_or_url);
    if ($val === '' || stripos($val, 'tel:') === 0) {
        $option_number = function_exists('get_field') ? get_field('whatsapp_number', 'option') : '';
        $val = trim((string) $option_number);
    }
    if ($val === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $val)) {
        return $val;
    }
    $clean = preg_replace('/\D+/', '', $val);
    if ($clean === '') {
        return '';
    }
    if (str_starts_with($clean, '0')) {
        $clean = '61' . substr($clean, 1);
    }
    return 'https://wa.me/' . $clean;
}

/** Save and load ACF JSON in the theme so field changes are version controlled. */
add_filter('acf/settings/save_json', function ($path) {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
    $theme_json_dir = get_stylesheet_directory() . '/acf-json';
    if (!in_array($theme_json_dir, $paths, true)) {
        $paths[] = $theme_json_dir;
    }
    return $paths;
});
