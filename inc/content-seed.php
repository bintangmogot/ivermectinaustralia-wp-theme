<?php
/**
 * Versioned content seed, so admin fields are filled in every environment after a git pull.
 *
 * Runs once per environment per IVERMECTIN_CONTENT_SEED_VERSION on acf/init.
 * It never overwrites content someone edited in WP admin:
 * - Theme Settings: only empty options are filled, from the field's ACF default.
 * - Homepage page_modules: written when empty, or when unchanged since the last seed.
 * The previous theme's "modules" field is only read, never written.
 */

defined('ABSPATH') || exit;

const IVERMECTIN_CONTENT_SEED_VERSION = 3;

add_action('acf/init', 'ivermectin_run_content_seed', 99);

function ivermectin_run_content_seed(bool $force = false): void {
    if (!function_exists('update_field') || (!$force && (int) get_option('ivermectin_content_seed_version') >= IVERMECTIN_CONTENT_SEED_VERSION)) {
        return;
    }
    ivermectin_seed_theme_settings();
    ivermectin_seed_logo_images();
    ivermectin_seed_homepage($force);
    ivermectin_fill_module_defaults();
    update_option('ivermectin_content_seed_version', IVERMECTIN_CONTENT_SEED_VERSION, false);
}

/**
 * Module sub-fields added after the homepage was first seeded. Rows that never had them get the ACF default;
 * a value someone saved in admin, even an empty one, is left alone.
 */
function ivermectin_fill_module_defaults(): void {
    $page_id = (int) get_option('page_on_front');
    $field   = acf_get_field('page_modules');
    if (!$page_id || !$field) {
        return;
    }
    $new_fields = [
        'hero_section'     => ['review_badge_text', 'show_mascot'],
        'reviews_carousel' => ['score_text', 'verified_label', 'rating_label', 'previous_label', 'next_label'],
        'how_to_order'     => ['show_payment_logos'],
        'support_cta'      => ['show_mascot'],
        'rich_text'        => ['faq_open'],
    ];
    $untouched  = md5(serialize(get_field($field['key'], $page_id, false))) === (string) get_option('ivermectin_homepage_seed_hash');
    $sub_fields = [];
    foreach ($field['layouts'] as $layout) {
        foreach ($layout['sub_fields'] as $sub_field) {
            if (in_array($sub_field['name'], $new_fields[$layout['name']] ?? [], true)) {
                $sub_fields[$layout['name']][] = $sub_field;
            }
        }
    }
    foreach ((array) get_post_meta($page_id, 'page_modules', true) as $index => $layout_name) {
        foreach ($sub_fields[$layout_name] ?? [] as $sub_field) {
            $meta_key = "page_modules_{$index}_{$sub_field['name']}";
            if (!metadata_exists('post', $page_id, $meta_key)) {
                update_post_meta($page_id, $meta_key, $sub_field['default_value']);
                update_post_meta($page_id, '_' . $meta_key, $sub_field['key']);
            }
        }
    }
    // Keep the "unchanged since the last seed" check working for future seed versions.
    if ($untouched) {
        update_option('ivermectin_homepage_seed_hash', md5(serialize(get_field($field['key'], $page_id, false))), false);
    }
}

/**
 * Footer payment and shipping logos (also shown under the homepage order steps). When both lists are empty,
 * the logos bundled with the theme are added to the Media Library once and listed there, so they can be swapped in admin.
 */
function ivermectin_seed_logo_images(): void {
    $lists = [
        'footer_payment_images'  => [['payid.png', 'PayID'], ['osko.jpg', 'Osko']],
        'footer_shipping_images' => [['australia-post.png', 'Australia Post']],
    ];
    foreach ($lists as $field_name => $logos) {
        if (get_field($field_name, 'option')) {
            continue;
        }
        $rows = [];
        foreach ($logos as [$file, $alt]) {
            $attachment_id = ivermectin_import_theme_image($file, $alt);
            if ($attachment_id) {
                $rows[] = ['image' => $attachment_id, 'alt_text' => $alt];
            }
        }
        if ($rows) {
            update_field($field_name, $rows, 'option');
        }
    }
}

/** Copy an image from assets/images into the Media Library once; later calls return the same attachment. */
function ivermectin_import_theme_image(string $file, string $alt): int {
    $imported = (array) get_option('ivermectin_imported_theme_images', []);
    if (!empty($imported[$file]) && get_post((int) $imported[$file])) {
        return (int) $imported[$file];
    }
    $source = IVERMECTIN_THEME_DIR . '/assets/images/' . $file;
    if (!is_readable($source)) {
        return 0;
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $temp = wp_tempnam($file);
    if (!$temp || !copy($source, $temp)) {
        return 0;
    }
    $attachment_id = media_handle_sideload(['name' => $file, 'tmp_name' => $temp], 0, $alt);
    if (is_wp_error($attachment_id)) {
        @unlink($temp);
        return 0;
    }
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
    $imported[$file] = (int) $attachment_id;
    update_option('ivermectin_imported_theme_images', $imported, false);
    return (int) $attachment_id;
}

/** Fill empty Theme Settings options from their ACF defaults. The shipping note waits for the client's decision. */
function ivermectin_seed_theme_settings(): void {
    $skip_types = ['tab', 'message', 'accordion', 'repeater', 'flexible_content', 'group', 'clone', 'image', 'file', 'gallery', 'post_object', 'relationship'];
    foreach (['group_theme_settings', 'group_product_presentation_settings'] as $group_key) {
        foreach ((array) acf_get_fields($group_key) as $field) {
            $default = $field['default_value'] ?? '';
            if (in_array($field['type'], $skip_types, true) || $default === '' || $default === null || $field['name'] === 'checkout_shipping_note') {
                continue;
            }
            if (get_option('options_' . $field['name'], '__unset__') === '__unset__') {
                update_field($field['key'], $default, 'option');
            }
        }
    }
}

/** Homepage modules built from the site's existing homepage content plus short section copy. */
function ivermectin_seed_homepage(bool $force = false): void {
    $page_id = (int) get_option('page_on_front');
    $field   = acf_get_field('page_modules');
    if (!$page_id || !$field) {
        return;
    }

    $current      = get_field($field['key'], $page_id, false);
    $current_hash = $current ? md5(serialize($current)) : '';
    $seeded_hash  = (string) get_option('ivermectin_homepage_seed_hash');
    if (!$force && $current && $current_hash !== $seeded_hash) {
        return;
    }

    update_field($field['key'], ivermectin_homepage_seed_rows($page_id), $page_id);
    update_option('ivermectin_homepage_seed_hash', md5(serialize(get_field($field['key'], $page_id, false))), false);
}

function ivermectin_homepage_seed_rows(int $page_id): array {
    return [
        [
            'acf_fc_layout'       => 'hero_section',
            'background_image'    => 3466,
            'location_text'       => 'Australia-wide delivery',
            'heading'             => 'Ivermectin Australia',
            'subtitle'            => 'Private, Reliable Ivermectin Delivery Across Australia',
            'description'         => 'Skip the hassle. Order your ivermectin online with simple PayID or bank transfer checkout, 100% unbranded plain packaging, and fully tracked postal delivery across Australia.',
            'primary_button_text' => 'Buy Ivermectin Online',
            'primary_button_link' => '/shop/',
            'stats'               => [
                ['value' => '3 to 40 mg', 'label' => 'Tablet strengths'],
                ['value' => 'PayID', 'label' => 'Or bank transfer in AUD'],
                ['value' => 'Tracked', 'label' => 'Australia Post delivery'],
            ],
        ],
        [
            'acf_fc_layout' => 'trust_strip',
            'badges'        => [
                ['icon_preset' => 'shield_insurance', 'icon_image' => '', 'label' => 'Sealed Blister Packs', 'desc' => 'Tamper-evident aluminium packaging protects tablet freshness and safety.'],
                ['icon_preset' => 'discreet_sealed', 'icon_image' => '', 'label' => '100% Plain Packaging', 'desc' => 'Delivered in unbranded mailing satchels for total privacy.'],
                ['icon_preset' => 'bank_transfer', 'icon_image' => '', 'label' => 'Simple Local Payments', 'desc' => 'Pay in AUD via PayID or standard domestic bank transfer.'],
                ['icon_preset' => 'track_parcel', 'icon_image' => '', 'label' => 'Australia Post Tracking', 'desc' => 'Fast, traceable delivery to metro and regional postcodes.'],
            ],
        ],
        [
            'acf_fc_layout' => 'featured_products',
            'subheading'    => 'Shop by strength',
            'title'         => 'Available Ivermectin Strengths & Pricing',
            'products'      => [3574, 3595, 3602, 3613, 3625],
            'button_text'   => 'View all products',
        ],
        [
            'acf_fc_layout' => 'how_to_order',
            'tag'           => 'How it works',
            'heading'       => 'Order in three simple steps',
            'image'         => 3815,
            'steps'         => [
                ['icon_preset' => 'browse_catalogue', 'title' => 'Choose your strength', 'desc' => 'Pick from 3 mg up to 40 mg tablets and the pack size that suits you.'],
                ['icon_preset' => 'bank_transfer', 'title' => 'Pay by PayID or bank transfer', 'desc' => 'Check out in AUD, then pay with PayID or a standard domestic bank transfer.'],
                ['icon_preset' => 'track_parcel', 'title' => 'Track your parcel', 'desc' => 'Your order ships in plain packaging with an Australia Post tracking link sent to your email.'],
            ],
        ],
        [
            'acf_fc_layout' => 'why_choose_us',
            'tag'           => 'Why order with us',
            'heading'       => 'Key Benefits of Ordering With Us',
            'image'         => 3750,
            'audiences'     => [
                ['icon_preset' => 'listing_details', 'title' => 'Flexible strength options', 'desc' => 'Choose from 3 mg up to 40 mg tablets to match your dosing schedule with fewer daily pills.'],
                ['icon_preset' => 'discreet_sealed', 'title' => 'Guaranteed privacy', 'desc' => 'Every order ships in a plain, unbranded satchel with no medical markings on the outside.'],
                ['icon_preset' => 'aud_checkout', 'title' => 'Local payments in AUD', 'desc' => 'Pay by instant PayID or domestic bank transfer, with no international card fees.'],
                ['icon_preset' => 'australia_delivery', 'title' => 'Reliable tracking', 'desc' => 'An Australia Post tracking link is emailed to you as soon as your parcel ships.'],
            ],
        ],
        [
            'acf_fc_layout' => 'reviews_carousel',
            'title'         => 'Customer Reviews',
        ],
        [
            'acf_fc_layout'  => 'support_cta',
            'eyebrow'        => 'Need a hand?',
            'title'          => 'Questions before you order?',
            'desc'           => 'Call or email our support team about strengths, payment or delivery.',
            'chat_btn_text'  => '0488 841 833',
            'chat_btn_url'   => 'tel:0488841833',
            'email_btn_text' => 'orders@ivermectin-australia.com.au',
        ],
        [
            'acf_fc_layout' => 'rich_text',
            'content'       => (string) get_post_meta($page_id, 'modules_3_content', true),
            'width'         => 'narrow',
            'image'         => 3762,
        ],
        [
            'acf_fc_layout' => 'blog_section',
            'title'         => 'Blog',
            'subtitle'      => 'Learn more about how Ivermectin affects your body and how to buy Ivermectin legally in Australia',
            'posts_count'   => 3,
        ],
    ];
}
