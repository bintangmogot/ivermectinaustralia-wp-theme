<?php
/**
 * Keep city fields available immediately and provide automated idempotent migration
 * for Australia Delivery cities across local, staging (InstaWP), and production environments.
 */
defined('ABSPATH') || exit;

add_action('acf/init', static function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    // Register the version-controlled definitions after ACF loads database groups.
    foreach (['group_city_fields', 'group_page_modules', 'group_theme_settings'] as $key) {
        $path = get_theme_file_path("/acf-json/{$key}.json");
        if (file_exists($path)) {
            $group = json_decode(file_get_contents($path), true);
            if (is_array($group) && !empty($group['key'])) {
                acf_add_local_field_group($group);
            }
        }
    }
});

/**
 * Automatically synchronize Australia Delivery cities on environments like InstaWP.
 * Replaces legacy Perth suburban posts with the 8 requested metropolitan delivery hubs.
 */
function waklert_auto_sync_delivery_cities() {
    $target_version = '20261020_v2';
    $current_version = get_option('waklert_delivery_cities_migration_ver');
    $force = isset($_GET['sync_cities_now']) && $_GET['sync_cities_now'] === '1';

    if ($current_version === $target_version && !$force) {
        return;
    }

    $seed_file = get_theme_file_path('/inc/data/delivery-cities-seed.json');
    if (!file_exists($seed_file)) {
        return;
    }

    $cities_data = json_decode(file_get_contents($seed_file), true);
    if (!is_array($cities_data) || empty($cities_data)) {
        return;
    }

    $target_slugs = [
        'sydney',
        'melbourne',
        'adelaide',
        'darwin',
        'brisbane',
        'gold-coast',
        'hobart',
        'canberra'
    ];

    // 1. Remove outdated / non-target city posts (e.g. legacy Perth suburbs)
    $existing_cities = get_posts([
        'post_type'   => 'city',
        'numberposts' => -1,
        'post_status' => 'any',
    ]);

    if (!empty($existing_cities)) {
        foreach ($existing_cities as $ec) {
            if (!in_array($ec->post_name, $target_slugs, true)) {
                wp_delete_post($ec->ID, true);
            }
        }
    }

    // 2. Discover available WooCommerce products dynamically
    $available_product_ids = [];
    $candidate_slugs = ['waklert-150mg', 'waklert-150mg-2', 'artvigil-150mg', 'armod-150mg', 'armodacharge-150mg', 'armod-50mg', 'waklert-50mg'];
    foreach ($candidate_slugs as $cslug) {
        $p = get_page_by_path($cslug, OBJECT, 'product');
        if ($p && $p->post_status === 'publish') {
            $available_product_ids[] = (int) $p->ID;
        }
    }
    if (empty($available_product_ids)) {
        $fallback_prods = get_posts([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 4,
            'fields'         => 'ids',
        ]);
        if (!empty($fallback_prods)) {
            $available_product_ids = array_map('intval', $fallback_prods);
        }
    }

    // 3. Upsert the 8 cities with full meta & ACF values
    $seeded_city_posts = [];
    foreach ($cities_data as $city) {
        $slug = $city['name'];
        if (!in_array($slug, $target_slugs, true)) {
            continue;
        }

        $existing = get_page_by_path($slug, OBJECT, 'city');
        $post_data = [
            'post_title'   => $city['title'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'city',
            'menu_order'   => (int) ($city['menu_order'] ?? 0),
        ];

        if ($existing) {
            $post_data['ID'] = $existing->ID;
            $post_id = wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        $seeded_city_posts[$slug] = $post_id;

        // Prepare ACF payload
        $acf_data = $city['acf'] ?? [];
        if (!empty($available_product_ids)) {
            $acf_data['selected_products'] = array_slice($available_product_ids, 0, 4);
        }

        if (function_exists('update_field')) {
            foreach ($acf_data as $key => $val) {
                update_field($key, $val, $post_id);
            }
        }

        // Store direct postmeta entries as backup
        if (!empty($city['meta']) && is_array($city['meta'])) {
            foreach ($city['meta'] as $meta_key => $meta_vals) {
                if (empty($meta_vals)) continue;
                $val = maybe_unserialize($meta_vals[0]);
                if ($meta_key === 'selected_products' && !empty($available_product_ids)) {
                    $val = array_slice($available_product_ids, 0, 4);
                }
                update_post_meta($post_id, $meta_key, $val);
            }
        }
    }

    // 4. Update Footer Delivery Cities Nav Menu
    $menu_obj = wp_get_nav_menu_object('footer-delivery-cities');
    if (!$menu_obj) {
        $menu_obj = wp_get_nav_menu_object('Footer Delivery Cities');
    }
    if (!$menu_obj) {
        $locations = get_nav_menu_locations();
        if (!empty($locations['footer_cities'])) {
            $menu_obj = wp_get_nav_menu_object($locations['footer_cities']);
        }
    }

    if ($menu_obj) {
        $menu_id = $menu_obj->term_id;
        $old_items = wp_get_nav_menu_items($menu_id);
        if ($old_items) {
            foreach ($old_items as $oi) {
                wp_delete_post($oi->ID, true);
            }
        }

        $menu_order = 1;
        foreach ($target_slugs as $tslug) {
            if (isset($seeded_city_posts[$tslug])) {
                $c_post_id = $seeded_city_posts[$tslug];
                $label = function_exists('get_field') ? get_field('city_name', $c_post_id) : '';
                if (empty($label)) {
                    $label = ucwords(str_replace('-', ' ', $tslug));
                }
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'     => $label,
                    'menu-item-object'    => 'city',
                    'menu-item-object-id' => $c_post_id,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $menu_order++,
                ]);
            }
        }
    }

    // 5. Update front page city_section module if applicable
    $front_id = (int) get_option('page_on_front');
    if ($front_id && function_exists('get_field') && function_exists('update_field')) {
        foreach (['modules', 'page_modules'] as $module_field_key) {
            $modules = get_field($module_field_key, $front_id);
            if (is_array($modules)) {
                $updated = false;
                foreach ($modules as &$mod) {
                    if (($mod['acf_fc_layout'] ?? '') === 'city_section') {
                        $mod['cities_list'] = [
                            ['city_name' => 'Sydney', 'has_checkmark' => true],
                            ['city_name' => 'Melbourne', 'has_checkmark' => true],
                            ['city_name' => 'Adelaide', 'has_checkmark' => true],
                            ['city_name' => 'Darwin', 'has_checkmark' => true],
                            ['city_name' => 'Brisbane', 'has_checkmark' => true],
                            ['city_name' => 'Gold Coast', 'has_checkmark' => true],
                            ['city_name' => 'Hobart', 'has_checkmark' => true],
                            ['city_name' => 'Canberra', 'has_checkmark' => true],
                        ];
                        $mod['quick_cities'] = "Sydney ✓\nMelbourne ✓\nAdelaide ✓\nDarwin ✓\nBrisbane ✓\nGold Coast ✓\nHobart ✓\nCanberra ✓";
                        $updated = true;
                    }
                }
                unset($mod);
                if ($updated) {
                    update_field($module_field_key, $modules, $front_id);
                }
            }
        }
    }

    // 6. Ensure Support CTA representative media image is imported & configured
    waklert_sync_support_cta_media();

    // 7. Synchronize variation menu_order across all products to ensure ascending pack sizes
    if (function_exists('waklert_sync_all_variations_menu_order')) {
        waklert_sync_all_variations_menu_order();
    }

    // 8. Synchronize Track Order page ACF flexible content & quick links pills
    if (function_exists('waklert_sync_track_order_page_module')) {
        waklert_sync_track_order_page_module();
    }

    // 9. Flush rewrites & mark migration complete
    flush_rewrite_rules(false);
    update_option('waklert_delivery_cities_migration_ver', $target_version);
}
add_action('init', 'waklert_auto_sync_delivery_cities', 20);

/**
 * Ensure the representative guy-with-phone image is imported into the WordPress Media Library
 * and set as the default media object for Support CTA, enabling the admin to edit or replace it.
 */
function waklert_sync_support_cta_media() {
    $source_file = get_theme_file_path('/assets/images/guy-with-phone.png');
    if (!file_exists($source_file)) {
        $source_file = get_theme_file_path('/assets/images/guy with phone.png');
    }
    if (!file_exists($source_file)) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    global $wpdb;
    $found_id = (int) get_option('waklert_support_cta_media_id');
    if (!$found_id || !get_post($found_id)) {
        $found_id = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_title = %s",
            'guy-with-phone'
        ));
    }

    $upload_dir = wp_upload_dir();
    $filename = 'guy-with-phone.png';

    if (!$found_id) {
        $dest_file = $upload_dir['path'] . '/' . $filename;
        copy($source_file, $dest_file);

        $filetype = wp_check_filetype($filename, null);
        $attachment = [
            'post_mime_type' => $filetype['type'],
            'post_title'     => 'guy-with-phone',
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $found_id = wp_insert_attachment($attachment, $dest_file);
        if (!is_wp_error($found_id) && $found_id) {
            $attach_data = wp_generate_attachment_metadata($found_id, $dest_file);
            wp_update_attachment_metadata($found_id, $attach_data);
            update_post_meta($found_id, '_wp_attachment_image_alt', 'Waklert Australia Customer Support - 24/7 Phone & WhatsApp');
        }
    } else {
        // If attachment exists, verify the file matches our latest cropped version
        $existing_file = get_attached_file($found_id);
        if ($existing_file && file_exists($existing_file)) {
            if (filesize($existing_file) !== filesize($source_file)) {
                copy($source_file, $existing_file);
                $attach_data = wp_generate_attachment_metadata($found_id, $existing_file);
                wp_update_attachment_metadata($found_id, $attach_data);
            }
        }
    }

    if ($found_id && !is_wp_error($found_id)) {
        update_option('waklert_support_cta_media_id', $found_id);
        if (function_exists('update_field')) {
            update_field('support_cta_image', $found_id, 'option');
        }
        update_option('options_support_cta_image', $found_id);

        // Ensure support_cta module is registered and configured on front page
        $front_id = (int) get_option('page_on_front');
        if ($front_id && function_exists('get_field') && function_exists('update_field')) {
            foreach (['modules', 'page_modules'] as $mod_key) {
                $modules = get_field($mod_key, $front_id);
                if (is_array($modules)) {
                    $has_support_cta = false;
                    $changed = false;
                    foreach ($modules as &$mod) {
                        if (($mod['acf_fc_layout'] ?? '') === 'support_cta') {
                            $has_support_cta = true;
                            if (empty($mod['image'])) {
                                $mod['image'] = $found_id;
                                $changed = true;
                            }
                        }
                    }
                    unset($mod);

                    if (!$has_support_cta) {
                        $scta_payload = [
                            'acf_fc_layout'   => 'support_cta',
                            'eyebrow'         => 'Need Help?',
                            'title'           => 'Talk to us via SMS or WhatsApp - 24/7.',
                            'desc'            => 'We are available all the time to handle any questions, offer advice or reassure you about your purchase.',
                            'description'     => 'We are available all the time to handle any questions, offer advice or reassure you about your purchase.',
                            'chat_btn_text'   => 'Contact Support',
                            'chat_btn_url'    => 'https://wa.me/61488841833',
                            'sms_btn_text'    => 'SMS',
                            'sms_btn_url'     => 'sms:0488841833',
                            'email_btn_text'  => 'Email Support',
                            'email_btn_url'   => 'mailto:orders@waklertaustralia.com.au',
                            'image'           => $found_id,
                        ];

                        // Find index of blog_section to place support_cta immediately after it
                        $blog_idx = -1;
                        foreach ($modules as $idx => $m) {
                            if (($m['acf_fc_layout'] ?? '') === 'blog_section') {
                                $blog_idx = $idx;
                                break;
                            }
                        }

                        if ($blog_idx !== -1) {
                            array_splice($modules, $blog_idx + 1, 0, [$scta_payload]);
                        } else {
                            $modules[] = $scta_payload;
                        }
                        $changed = true;
                    }

                    if ($changed) {
                        update_field($mod_key, $modules, $front_id);
                    }
                }
            }
        }

        return $found_id;
    }

    return 0;
}


/**
 * Synchronize Track Order page ACF fields so admin can easily edit all headlines and quick links pills.
 */
function waklert_sync_track_order_page_module() {
    $page = get_page_by_path('track-order');
    if (!$page) {
        $pages = get_posts([
            'post_type'   => 'page',
            'meta_key'    => '_wp_page_template',
            'meta_value'  => 'page-track-order.php',
            'numberposts' => 1,
        ]);
        if (!empty($pages)) {
            $page = $pages[0];
        }
    }
    if (!$page) {
        $p633 = get_post(633);
        if ($p633 && $p633->post_type === 'page') {
            $page = $p633;
        }
    }
    if (!$page) {
        return false;
    }

    $page_id = $page->ID;

    $track_module = [
        'acf_fc_layout'        => 'track_order',
        'badge_text'           => 'Live Australia Post Tracking',
        'heading'              => 'Track Your Parcel',
        'description'          => 'Follow your Waklert Australia delivery from our warehouse to your doorstep with real-time Australia Post tracking.',
        'intro_callout_p1'     => 'Welcome to the Waklert Australia order tracking page. Every parcel we dispatch is shipped with Australia Post and comes with a unique tracking number, so you can follow your delivery from our warehouse to your door.',
        'intro_callout_p2'     => "Your tracking number was emailed to you when your order was dispatched. Enter it below and we'll take you straight to the live Australia Post tracking results for your parcel.",
        'form_heading'         => 'Enter Your Tracking Number',
        'form_instructions'    => 'Tracking numbers are usually 10–23 characters long, e.g. 33ABC1234567. You can find yours in your dispatch confirmation email.',
        'input_placeholder'    => 'e.g. 33ABC1234567',
        'track_btn_text'       => 'Track My Order',
        'redirect_notice'      => "You'll be redirected to the official Australia Post tracking page (auspost.com.au), where your parcel's latest scan events are shown automatically.",
        'how_works_heading'    => 'How Order Tracking Works',
        'how_works_steps'      => [
            [
                'title'       => '1. Order Dispatched',
                'description' => 'Once your order leaves our facility, we email you a dispatch confirmation containing your Australia Post tracking number.',
            ],
            [
                'title'       => '2. Enter Your Number',
                'description' => 'Paste the tracking number into the field above and select "Track My Order". We\'ll open your parcel\'s results on the Australia Post website.',
            ],
            [
                'title'       => '3. Follow Your Parcel',
                'description' => 'Australia Post updates scan events at each stage — accepted, in transit, onboard for delivery, and delivered.',
            ],
        ],
        'help_heading'         => 'Need Help?',
        'help_description'     => "Can't find your tracking number or no scans after 3 business days? Contact our support team and we'll look into it.",
        'help_btn_text'        => 'Contact Support',
        'help_btn_url'         => home_url('/contact/'),
        'trust_cards'          => [
            [
                'card_type'   => 'shipping',
                'title'       => 'Free Shipping',
                'description' => 'On orders over $200 AUD — enjoy fast, secure, and discreet delivery right to your doorstep.',
            ],
            [
                'card_type'   => 'support',
                'title'       => '24/7 Support',
                'description' => 'We’re here to help anytime — day or night, our team is ready to assist you with your orders and inquiries.',
            ],
            [
                'card_type'   => 'payment',
                'title'       => 'Secure Payment',
                'description' => 'Shop with confidence — all transactions are 100% safe, encrypted, and protected for your peace of mind.',
            ],
        ],
    ];

    if (function_exists('update_field')) {
        update_field('field_modules', [$track_module], $page_id);
    } else {
        update_post_meta($page_id, 'modules', ['track_order']);
        update_post_meta($page_id, '_modules', 'field_modules');
    }

    // Clean up any legacy quick links postmeta
    delete_post_meta($page_id, 'modules_0_quick_links_heading');
    delete_post_meta($page_id, '_modules_0_quick_links_heading');
    delete_post_meta($page_id, 'modules_0_keyword_pills');
    delete_post_meta($page_id, '_modules_0_keyword_pills');
    for ($i = 0; $i < 20; $i++) {
        delete_post_meta($page_id, "modules_0_keyword_pills_{$i}_label");
        delete_post_meta($page_id, "_modules_0_keyword_pills_{$i}_label");
        delete_post_meta($page_id, "modules_0_keyword_pills_{$i}_url");
        delete_post_meta($page_id, "_modules_0_keyword_pills_{$i}_url");
        delete_post_meta($page_id, "modules_0_keyword_pills_{$i}_is_external");
        delete_post_meta($page_id, "_modules_0_keyword_pills_{$i}_is_external");
    }

    clean_post_cache($page_id);
    return true;
}
