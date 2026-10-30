<?php
/**
 * ACF Theme Options Page
 */

if (function_exists('acf_add_options_page')) {
    add_action('acf/init', function() {
        acf_add_options_page(array(
            'page_title'    => 'Theme General Settings',
            'menu_title'    => 'Theme Settings',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-admin-generic',
            'position'      => 30
        ));
    });
}

/**
 * Format a phone number or URL into a clean WhatsApp direct chat URL.
 * Defaults to the Theme Settings WhatsApp number or +61 488 841 833.
 */
function waklert_format_whatsapp_url($phone_or_url = null) {
    $val = trim((string) $phone_or_url);
    if ($val === '' || stripos($val, 'tel:') === 0) {
        $option_number = function_exists('get_field') ? get_field('whatsapp_number', 'option') : null;
        $val = !empty($option_number) ? trim((string) $option_number) : '+61 488 841 833';
    }

    // Direct URL provided
    if (preg_match('#^https?://#i', $val)) {
        // Replace legacy Malaysian number if present
        if (strpos($val, 'wa.me/60') !== false) {
            return 'https://wa.me/61488841833';
        }
        return $val;
    }

    // Strip all non-digits
    $clean = preg_replace('/\D+/', '', $val);

    // If starts with Australian local mobile 04..., convert leading 0 to 61
    if (str_starts_with($clean, '0')) {
        $clean = '61' . substr($clean, 1);
    }

    // If starts with Malaysian country code 60 or empty, use Australian fallback
    if ($clean === '' || str_starts_with($clean, '60')) {
        $clean = '61488841833';
    }

    return 'https://wa.me/' . $clean;
}

/**
 * Configure ACF JSON save and load paths for automatic sync
 */
add_filter('acf/settings/save_json', function($path) {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function($paths) {
    $theme_json_dir = get_stylesheet_directory() . '/acf-json';
    if (!in_array($theme_json_dir, $paths)) {
        $paths[] = $theme_json_dir;
    }
    return $paths;
});

/**
 * Synchronize Header & Top Bar Theme Settings across local, staging (InstaWP), and live environments:
 * - Imports acf-json/group_theme_settings.json into any existing DB field group so WP Admin labels update immediately without deleting the JSON file
 * - Seeds live Header & Top Bar values once so WP Admin reflects the live configuration out of the box
 */
add_action('acf/init', function() {
    $target_ver = '20261028_checkout_settings_v1';
    if (get_option('waklert_header_settings_sync_ver') === $target_ver) {
        return;
    }

    // 1. If a DB field group exists for group_theme_settings, sync it from acf-json/group_theme_settings.json
    $json_path = get_stylesheet_directory() . '/acf-json/group_theme_settings.json';
    if (file_exists($json_path) && function_exists('acf_import_field_group')) {
        $json_data = json_decode(file_get_contents($json_path), true);
        if (is_array($json_data) && !empty($json_data['key'])) {
            $acf_posts = get_posts([
                'post_type'      => 'acf-field-group',
                'posts_per_page' => -1,
                'post_status'    => 'any',
            ]);
            if (!empty($acf_posts)) {
                $first_matched = false;
                foreach ($acf_posts as $p) {
                    if ($p->post_name === 'group_theme_settings' || $p->post_excerpt === 'group_theme_settings' || $p->post_excerpt === 'theme-settings') {
                        if (!$first_matched) {
                            $first_matched = true;
                            $json_data['ID'] = $p->ID;
                            acf_import_field_group($json_data);
                        } else {
                            // Remove duplicate DB post directly without triggering acf_delete_field_group JSON unlink
                            wp_delete_post($p->ID, true);
                        }
                    }
                }
            }
        }
    }

    // 2. Sync Header & Top Bar options to match live data if still on legacy defaults
    $topbar_left = get_option('options_topbar_left');
    if ($topbar_left === false || $topbar_left === '' || strpos((string) $topbar_left, '7-10 Day Delivery') !== false) {
        update_option('options_topbar_left', 'Express Domestic Shipping');
        update_option('_options_topbar_left', 'field_topbar_left');
    }

    $topbar_center = get_option('options_topbar_center');
    if ($topbar_center === false || $topbar_center === '' || strpos((string) $topbar_center, 'Free Shipping on Orders') !== false) {
        update_option('options_topbar_center', 'Local Support 24/7');
        update_option('_options_topbar_center', 'field_topbar_center');
    }

    $phone_num = get_option('options_whatsapp_number');
    if ($phone_num === false || $phone_num === '' || strpos((string) $phone_num, '+61') !== false) {
        update_option('options_whatsapp_number', '0488 841 833');
        update_option('_options_whatsapp_number', 'field_whatsapp_number');
    }

    $btn_text = get_option('options_whatsapp_button_text');
    if ($btn_text === false || $btn_text === '' || stripos((string) $btn_text, 'whatsapp') !== false) {
        update_option('options_whatsapp_button_text', '0488 841 833');
        update_option('_options_whatsapp_button_text', 'field_whatsapp_button_text');
    }

    $btn_url = get_option('options_whatsapp_button_url');
    if ($btn_url === false || $btn_url === '' || stripos((string) $btn_url, 'wa.me') !== false) {
        update_option('options_whatsapp_button_url', 'tel:0488841833');
        update_option('_options_whatsapp_button_url', 'field_whatsapp_button_url');
    }

    // Clean up any accidental options_field_* entries
    delete_option('options_field_topbar_left');
    delete_option('_options_field_topbar_left');
    delete_option('options_field_topbar_center');
    delete_option('_options_field_topbar_center');
    delete_option('options_field_whatsapp_button_text');
    delete_option('_options_field_whatsapp_button_text');
    delete_option('options_field_whatsapp_button_url');
    delete_option('_options_field_whatsapp_button_url');

    // 3. Seed Checkout & Thank You options
    if (get_option('options_payid_number') === false) {
        update_option('options_payid_number', '0402 222 605');
        update_option('_options_payid_number', 'field_checkout_payid_number');
    }
    if (get_option('options_checkout_bacs_warning') === false) {
        update_option('options_checkout_bacs_warning', '* Use the below BSB & account details to make the transfer. Simply mention your order number in the comment section. DO NOT reference anything related to medicine or website name. Just mention your order number.');
        update_option('_options_checkout_bacs_warning', 'field_checkout_bacs_warning');
    }
    if (get_option('options_checkout_receipt_notice') === false) {
        update_option('options_checkout_receipt_notice', "* Once your payment is done, just send the transaction copy to {email} and we'll ship your order immediately.");
        update_option('_options_checkout_receipt_notice', 'field_checkout_receipt_notice');
    }
    if (get_option('options_checkout_shipping_note') === false) {
        update_option('options_checkout_shipping_note', 'The average shipping time is 10 - 15 days. Please note that delivery may take up to 30 days from the date of dispatch due to potential disruptions in postal services caused by weather issues or natural disaster.');
        update_option('_options_checkout_shipping_note', 'field_checkout_shipping_note');
    }

    // 4. Seed Order Confirmation Email options
    if (get_option('options_email_on_hold_intro') === false) {
        update_option('options_email_on_hold_intro', "We've received your order and it's currently on hold until we can confirm your payment has been processed.");
        update_option('_options_email_on_hold_intro', 'field_email_on_hold_intro');
    }
    if (get_option('options_email_on_hold_instruction') === false) {
        update_option('options_email_on_hold_instruction', 'Please complete your payment using EFT or PayID below with your order number and we will send the product immediately and also send you an Australian Post tracking number.');
        update_option('_options_email_on_hold_instruction', 'field_email_on_hold_instruction');
    }
    if (get_option('options_email_on_hold_warning') === false) {
        update_option('options_email_on_hold_warning', 'Please DO NOT mention WAKLERT or Armodafinil on your bank transfer description.');
        update_option('_options_email_on_hold_warning', 'field_email_on_hold_warning');
    }

    update_option('waklert_header_settings_sync_ver', $target_ver);
}, 20);

/**
 * Synchronize Page Modules ACF schema & seed semantically aligned icon presets + descriptions
 * across all pages (Homepage, About Us, Buyer's Guide, Track Order) so WP Admin shows the exact
 * matching colorful icon preset and description for every card out of the box.
 */
add_action('acf/init', function() {
    $target_ver = '20261021_section_icons_v3';
    if (get_option('waklert_section_icons_sync_ver') === $target_ver) {
        return;
    }

    // 1. If a DB field group exists for group_page_modules, sync it from acf-json/group_page_modules.json
    $json_path = get_stylesheet_directory() . '/acf-json/group_page_modules.json';
    if (file_exists($json_path) && function_exists('acf_import_field_group')) {
        $json_data = json_decode(file_get_contents($json_path), true);
        if (is_array($json_data) && !empty($json_data['key'])) {
            $acf_posts = get_posts([
                'post_type'      => 'acf-field-group',
                'posts_per_page' => -1,
                'post_status'    => 'any',
            ]);
            if (!empty($acf_posts)) {
                $first_matched = false;
                foreach ($acf_posts as $p) {
                    if ($p->post_name === 'group_page_modules' || $p->post_excerpt === 'group_page_modules' || $p->post_excerpt === 'page-modules') {
                        if (!$first_matched) {
                            $first_matched = true;
                            $json_data['ID'] = $p->ID;
                            acf_import_field_group($json_data);
                        } else {
                            wp_delete_post($p->ID, true);
                        }
                    }
                }
            }
        }
    }

    // 2. Seed icon_preset and badge descriptions across all published pages using modules
    if (function_exists('waklert_detect_icon_preset_from_text')) {
        $pages = get_posts([
            'post_type'      => 'page',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ]);

        $default_badge_descs = [
            "Tracked delivery via Australia Post to all states and territories.",
            "Direct batch-tested pharmaceuticals from accredited manufacturers.",
            "Encrypted checkout with local PayID & Australian bank EFT.",
            "Shipped in generic, unbranded satchels for complete privacy."
        ];

        foreach ($pages as $page) {
            $pid = $page->ID;
            $layouts = get_post_meta($pid, 'modules', true);
            if (!is_array($layouts) || empty($layouts)) {
                continue;
            }

            foreach ($layouts as $m_idx => $layout_name) {
                // A. why_choose_us -> audiences repeater
                if ($layout_name === 'why_choose_us') {
                    $count = (int) get_post_meta($pid, "modules_{$m_idx}_audiences", true);
                    for ($r = 0; $r < $count; $r++) {
                        $title = (string) get_post_meta($pid, "modules_{$m_idx}_audiences_{$r}_title", true);
                        $desc  = (string) get_post_meta($pid, "modules_{$m_idx}_audiences_{$r}_desc", true);
                        $preset = waklert_detect_icon_preset_from_text($title, $desc, 'parcel_guarantee');
                        update_post_meta($pid, "modules_{$m_idx}_audiences_{$r}_icon_preset", $preset);
                        update_post_meta($pid, "_modules_{$m_idx}_audiences_{$r}_icon_preset", 'field_wcu_a_icon_preset');
                        // Clear legacy monochrome stroke="currentColor" wireframe SVGs so WP Admin is clean
                        $old_svg = (string) get_post_meta($pid, "modules_{$m_idx}_audiences_{$r}_icon_svg", true);
                        if (stripos($old_svg, 'stroke="currentColor"') !== false) {
                            update_post_meta($pid, "modules_{$m_idx}_audiences_{$r}_icon_svg", '');
                        }
                    }
                }

                // B. trust_strip -> badges repeater
                if ($layout_name === 'trust_strip') {
                    $count = (int) get_post_meta($pid, "modules_{$m_idx}_badges", true);
                    for ($r = 0; $r < $count; $r++) {
                        $label = (string) get_post_meta($pid, "modules_{$m_idx}_badges_{$r}_label", true);
                        $desc  = (string) get_post_meta($pid, "modules_{$m_idx}_badges_{$r}_desc", true);
                        $lower = strtolower(trim($label));
                        if (trim($desc) === '' || (str_contains($lower, 'checkout') && str_contains($lower, 'delivery') && str_contains($desc, 'active ingredient'))) {
                            if (str_contains($lower, 'focused') || str_contains($lower, 'category')) {
                                $desc = 'Dedicated exclusively to genuine armodafinil tablets and verified pack sizes.';
                            } elseif (str_contains($lower, 'checkout') && str_contains($lower, 'delivery')) {
                                $desc = 'Transparent shipping options, transit estimates, and Australia Post tracking at checkout.';
                            } elseif (str_contains($lower, 'listing') || str_contains($lower, 'detail')) {
                                $desc = 'Clear active ingredient, strength, manufacturer, and pack options on every page.';
                            } elseif (str_contains($lower, 'support') || str_contains($lower, 'question')) {
                                $desc = 'Direct Australian customer care via SMS, WhatsApp, and email 7 days a week.';
                            } elseif (str_contains($lower, 'sealed')) {
                                $desc = 'Original factory-sealed blister packs from licensed pharmaceutical manufacturers.';
                            } elseif (str_contains($lower, 'discreet')) {
                                $desc = 'Plain, unmarked satchels with no external branding for complete privacy.';
                            } elseif (str_contains($lower, 'checkout') || str_contains($lower, 'secure')) {
                                $desc = 'Encrypted AUD checkout supporting local PayID and Australian bank transfer.';
                            } else {
                                $desc = $default_badge_descs[$r % 4] ?? $default_badge_descs[0];
                            }
                            update_post_meta($pid, "modules_{$m_idx}_badges_{$r}_desc", $desc);
                            update_post_meta($pid, "_modules_{$m_idx}_badges_{$r}_desc", 'field_ts_desc_en');
                        }
                        $preset = waklert_detect_icon_preset_from_text($label, $desc, 'australia_delivery');
                        update_post_meta($pid, "modules_{$m_idx}_badges_{$r}_icon_preset", $preset);
                        update_post_meta($pid, "_modules_{$m_idx}_badges_{$r}_icon_preset", 'field_ts_icon_preset');
                        $old_svg = (string) get_post_meta($pid, "modules_{$m_idx}_badges_{$r}_icon_svg", true);
                        if (stripos($old_svg, 'stroke="currentColor"') !== false) {
                            update_post_meta($pid, "modules_{$m_idx}_badges_{$r}_icon_svg", '');
                        }
                    }
                }

                // C. how_to_order -> steps repeater
                if ($layout_name === 'how_to_order') {
                    $sec_desc = (string) get_post_meta($pid, "modules_{$m_idx}_description", true);
                    if (trim($sec_desc) === '') {
                        update_post_meta($pid, "modules_{$m_idx}_description", 'Simple, transparent, and private. Ordering Armodafinil across Australia takes just 3 straightforward steps.');
                        update_post_meta($pid, "_modules_{$m_idx}_description", 'field_hto_desc_en');
                    }
                    $count = (int) get_post_meta($pid, "modules_{$m_idx}_steps", true);
                    for ($r = 0; $r < $count; $r++) {
                        $title = (string) get_post_meta($pid, "modules_{$m_idx}_steps_{$r}_title", true);
                        $desc  = (string) get_post_meta($pid, "modules_{$m_idx}_steps_{$r}_desc", true);
                        $preset = waklert_detect_icon_preset_from_text($title, $desc, 'browse_catalogue');
                        update_post_meta($pid, "modules_{$m_idx}_steps_{$r}_icon_preset", $preset);
                        update_post_meta($pid, "_modules_{$m_idx}_steps_{$r}_icon_preset", 'field_hto_s_icon_preset');
                        $old_svg = (string) get_post_meta($pid, "modules_{$m_idx}_steps_{$r}_icon_svg", true);
                        if (stripos($old_svg, 'stroke="currentColor"') !== false) {
                            update_post_meta($pid, "modules_{$m_idx}_steps_{$r}_icon_svg", '');
                        }
                    }
                }

                // D. about_delivery -> list_items repeater
                if ($layout_name === 'about_delivery') {
                    $count = (int) get_post_meta($pid, "modules_{$m_idx}_list_items", true);
                    for ($r = 0; $r < $count; $r++) {
                        $title = (string) get_post_meta($pid, "modules_{$m_idx}_list_items_{$r}_title", true);
                        $desc  = (string) get_post_meta($pid, "modules_{$m_idx}_list_items_{$r}_desc", true);
                        $preset = waklert_detect_icon_preset_from_text($title, $desc, 'express_shipping');
                        update_post_meta($pid, "modules_{$m_idx}_list_items_{$r}_icon_preset", $preset);
                        update_post_meta($pid, "_modules_{$m_idx}_list_items_{$r}_icon_preset", 'field_ad_icon_preset');
                        $old_svg = (string) get_post_meta($pid, "modules_{$m_idx}_list_items_{$r}_icon_svg", true);
                        if (stripos($old_svg, 'stroke="currentColor"') !== false) {
                            update_post_meta($pid, "modules_{$m_idx}_list_items_{$r}_icon_svg", '');
                        }
                    }
                }

                // E. about_mission
                if ($layout_name === 'about_mission') {
                    $m_preset = (string) get_post_meta($pid, "modules_{$m_idx}_mission_icon_preset", true);
                    if ($m_preset === '') {
                        update_post_meta($pid, "modules_{$m_idx}_mission_icon_preset", 'browse_catalogue');
                        update_post_meta($pid, "_modules_{$m_idx}_mission_icon_preset", 'field_am_mission_icon_preset');
                    }
                    $v_preset = (string) get_post_meta($pid, "modules_{$m_idx}_vision_icon_preset", true);
                    if ($v_preset === '') {
                        update_post_meta($pid, "modules_{$m_idx}_vision_icon_preset", 'medical_rx_clarity');
                        update_post_meta($pid, "_modules_{$m_idx}_vision_icon_preset", 'field_am_vision_icon_preset');
                    }
                }
            }
        }
    }

    update_option('waklert_section_icons_sync_ver', $target_ver);
}, 25);
