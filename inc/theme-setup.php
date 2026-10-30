<?php
/**
 * Theme Setup & Registration
 */

function modmy_theme_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // WooCommerce support
    add_theme_support('woocommerce');

    // Custom Logo support (WordPress standard)
    add_theme_support('custom-logo', array(
        'height'      => 90,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
        'unlink-homepage-logo' => false,
    ));

    // Register Navigation Menus
    register_nav_menus(array(
        'primary'         => __('Primary Navigation (Desktop)', 'waklert-australia'),
        'mobile'          => __('Mobile Navigation', 'waklert-australia'),
        'footer_quick'    => __('Footer Quick Links', 'waklert-australia'),
        'footer_info'     => __('Footer Information', 'waklert-australia'),
        'footer_trending' => __('Footer Trending Products', 'waklert-australia'),
        'footer_cities'   => __('Footer Delivery Cities', 'waklert-australia'),
    ));

    // HTML5 markup support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'modmy_theme_setup');

/**
 * Register Custom Post Types
 */
function modmy_register_cpt() {
    // Reviews CPT
    register_post_type('review', array(
        'labels' => array(
            'name' => 'Reviews',
            'singular_name' => 'Review',
            'add_new_item' => 'Add New Review'
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title'), // ACF will handle the rest
    ));
}
add_action('init', 'modmy_register_cpt');

/**
 * Add Tailwind CSS classes to Navigation Menus
 */
add_filter('nav_menu_css_class', function($classes, $item, $args, $depth = 0) {
    if (isset($args->walker) && ($args->walker instanceof Waklert_Desktop_Nav_Walker || $args->walker instanceof Waklert_Mobile_Nav_Walker)) {
        return $classes;
    }
    if (isset($args->theme_location)) {
        $is_mobile = ($args->theme_location === 'mobile') || (isset($args->menu_class) && strpos($args->menu_class, 'flex flex-col') !== false);
        $has_children = in_array('menu-item-has-children', $classes);

        if ($is_mobile) {
            $classes[] = 'w-full list-none';
            if ($has_children) {
                $classes[] = 'has-submenu';
            }
        } elseif ($args->theme_location === 'primary') {
            $classes[] = 'list-none';
            if ($has_children && $depth === 0) {
                $classes[] = 'relative group';
            }
        }
    }
    return $classes;
}, 10, 4);

add_filter('nav_menu_link_attributes', function($atts, $item, $args, $depth = 0) {
    if (isset($args->walker) && ($args->walker instanceof Waklert_Desktop_Nav_Walker || $args->walker instanceof Waklert_Mobile_Nav_Walker)) {
        return $atts;
    }
    if (isset($args->theme_location)) {
        $is_mobile = ($args->theme_location === 'mobile') || (isset($args->menu_class) && strpos($args->menu_class, 'flex flex-col') !== false);

        if ($is_mobile) {
            if ($depth === 0) {
                // Mobile Menu Root Link
                $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'flex-1 block px-6 py-3.5 text-[17px] font-semibold text-slate-800 hover:bg-stone-50 hover:text-primary transition-colors';
            } else {
                // Mobile Menu Sub-item Link
                $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'flex items-center justify-between w-full px-4 py-2.5 text-[15px] font-medium text-foreground/85 hover:text-primary hover:bg-white rounded-xl transition-colors';
            }
        } elseif ($args->theme_location === 'primary') {
            if ($depth === 0) {
                // Desktop Root Menu Link
                $classes = 'inline-flex items-center px-3.5 xl:px-4 py-2 text-[17.5px] font-semibold rounded-full transition-all duration-200';
                $item_classes = (array) $item->classes;
                $is_current = in_array('current-menu-item', $item_classes) || $item->current || in_array('current-page-ancestor', $item_classes);
                if ($is_current) {
                    $classes .= ' bg-primary-softer text-primary';
                } else {
                    $classes .= ' text-foreground/80 hover:bg-primary-softer hover:text-primary';
                }
                $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . $classes;
            } else {
                // Desktop Submenu Dropdown Link
                $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'flex items-center justify-between w-full px-3.5 py-2 text-[14.5px] font-semibold text-foreground/80 hover:text-primary hover:bg-primary-softer/70 rounded-xl transition-colors whitespace-nowrap';
            }
        } elseif (strpos($args->theme_location, 'footer_') !== false) {
            $atts['class'] = (isset($atts['class']) ? $atts['class'] . ' ' : '') . 'hover:text-primary transition-colors';
        }
    }
    return $atts;
}, 10, 4);

add_filter('nav_menu_submenu_css_class', function($classes, $args, $depth = 0) {
    if (isset($args->walker) && ($args->walker instanceof Waklert_Desktop_Nav_Walker || $args->walker instanceof Waklert_Mobile_Nav_Walker)) {
        return $classes;
    }
    if (isset($args->theme_location) && ($args->theme_location === 'primary' || $args->theme_location === 'mobile')) {
        if (!in_array('sub-menu', $classes)) {
            $classes[] = 'sub-menu';
        }
        $is_mobile = ($args->theme_location === 'mobile') || (isset($args->menu_class) && strpos($args->menu_class, 'flex flex-col') !== false);
        if ($is_mobile && !in_array('is-open', $classes)) {
            $classes[] = 'is-open';
        }
    }
    return $classes;
}, 10, 3);

add_filter('walker_nav_menu_start_el', function($item_output, $item, $depth, $args) {
    if (isset($args->walker) && ($args->walker instanceof Waklert_Desktop_Nav_Walker || $args->walker instanceof Waklert_Mobile_Nav_Walker)) {
        return $item_output;
    }
    if (isset($args->theme_location) && ($args->theme_location === 'primary' || $args->theme_location === 'mobile')) {
        $item_classes = (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $item_classes);
        $is_mobile = ($args->theme_location === 'mobile') || (isset($args->menu_class) && strpos($args->menu_class, 'flex flex-col') !== false);

        if ($has_children && $depth === 0) {
            if ($is_mobile) {
                $chevron_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4.5 h-4.5 pointer-events-none transition-transform duration-200"><path d="m6 9 6 6 6-6"/></svg>';
                $toggle_button = sprintf(
                    '<button type="button" class="mobile-submenu-toggle is-active flex items-center justify-center w-9 h-9 mr-6 rounded-xl bg-surface border border-border/70 text-foreground/70 hover:text-primary hover:bg-primary-softer hover:border-primary/40 transition-all focus:outline-none shrink-0" aria-expanded="true" aria-label="%s">%s</button>',
                    esc_attr(sprintf(__('Toggle %s submenu', 'waklert-australia'), $item->title)),
                    $chevron_svg
                );
                $item_output = '<div class="flex items-center justify-between w-full">' . $item_output . $toggle_button . '</div>';
            } else {
                $desktop_chevron = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="inline-block ml-1.5 w-3.5 h-3.5 opacity-60 group-hover:opacity-100 group-hover:rotate-180 transition-transform duration-200"><path d="m6 9 6 6 6-6"/></svg>';
                $item_output = str_replace('</a>', $desktop_chevron . '</a>', $item_output);
            }
        } elseif ($depth > 0 && $is_mobile) {
            $arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 opacity-40 shrink-0"><path d="m9 18 6-6-6-6"/></svg>';
            $item_output = str_replace('</a>', $arrow_svg . '</a>', $item_output);
        }
    }
    return $item_output;
}, 10, 4);

/**
 * Get dynamic site logo attachment ID
 * Checks ACF Theme Settings first, falls back to WordPress Custom Logo (theme mod)
 *
 * @param string $location 'header', 'mobile', or 'footer'
 * @return int Attachment ID or 0
 */
function waklert_get_logo_id($location = 'header') {
    if ($location === 'footer') {
        $footer_logo = function_exists('get_field') ? get_field('footer_logo', 'option') : null;
        if (!empty($footer_logo)) {
            return is_array($footer_logo) ? (int) ($footer_logo['ID'] ?? 0) : (int) $footer_logo;
        }
    }

    $acf_logo = function_exists('get_field') ? get_field('site_logo', 'option') : null;
    if (!empty($acf_logo)) {
        return is_array($acf_logo) ? (int) ($acf_logo['ID'] ?? 0) : (int) $acf_logo;
    }

    return (int) get_theme_mod('custom_logo');
}

function waklert_get_logo_url($location = 'header') {
    $logo_id = waklert_get_logo_id($location);
    if ($logo_id && ($src = wp_get_attachment_image_url($logo_id, 'full'))) {
        return $src;
    }
    // Fallback to theme asset for fresh environments (e.g. InstaWP staging)
    $theme_logo = get_template_directory() . '/assets/images/waklert-australia-logo.png';
    if (file_exists($theme_logo)) {
        return get_template_directory_uri() . '/assets/images/waklert-australia-logo.png';
    }
    return false;
}

/**
 * Render dynamic site logo markup (NOT hardcoded, editable in WP Customizer / ACF Theme Settings)
 *
 * @param string $classes CSS classes for the img tag
 * @param string $location 'header', 'mobile', or 'footer'
 */
function waklert_the_logo($classes = 'h-12 sm:h-[53px] w-auto max-w-[264px] sm:max-w-[312px] object-contain', $location = 'header') {
    $logo_src = waklert_get_logo_url($location);
    $home_url = esc_url(home_url('/'));
    $site_name = esc_attr(get_bloginfo('name') ?: 'Waklert Australia');

    if ($logo_src) {
        $logo_classes = trim($classes . ' site-logo-img site-logo-img--' . esc_attr($location));
        $inline_styles = $location === 'mobile'
            ? 'height: 43px; max-height: 43px; width: auto; max-width: 228px; object-fit: contain;'
            : 'height: 53px; max-height: 53px; width: auto; max-width: 312px; object-fit: contain;';

        $img_html = sprintf(
            '<img src="%s" alt="%s" class="%s" style="%s" width="288" height="98" loading="%s" decoding="async" />',
            esc_url($logo_src),
            $site_name,
            esc_attr($logo_classes),
            esc_attr($inline_styles),
            $location === 'header' ? 'eager' : 'lazy'
        );

        if ($location === 'footer') {
            // Check if it's the main logo (with dark text) on a dark footer background
            $has_footer_specific = function_exists('get_field') && get_field('footer_logo', 'option');
            $wrapper_class = $has_footer_specific
                ? 'inline-flex shrink-0 items-center'
                : 'inline-flex shrink-0 items-center rounded-xl bg-white px-3.5 py-1.5 shadow-sm hover:opacity-95 transition-opacity';
            echo sprintf('<a href="%s" class="%s" aria-label="%s">%s</a>', $home_url, $wrapper_class, $site_name, $img_html);
        } else {
            echo sprintf('<a href="%s" class="flex shrink-0 items-center" aria-label="%s">%s</a>', $home_url, $site_name, $img_html);
        }
        return;
    }

    // Dynamic text fallback if no logo image is uploaded
    if ($location === 'footer') {
        echo sprintf(
            '<a href="%s" class="flex shrink-0 items-center gap-2.5" aria-label="%s">
                <span class="font-heading text-xl font-extrabold tracking-tight text-white">%s</span>
            </a>',
            $home_url,
            $site_name,
            esc_html(get_bloginfo('name') ?: 'Waklert Australia')
        );
    } else {
        echo sprintf(
            '<a href="%s" class="flex shrink-0 items-center gap-2.5" aria-label="%s">
                <span class="font-heading text-[17px] font-extrabold tracking-[0.12em] text-foreground uppercase">%s</span>
            </a>',
            $home_url,
            $site_name,
            esc_html(get_bloginfo('name') ?: 'Waklert Australia')
        );
    }
}

/**
 * Wrap WYSIWYG tables in responsive table-wrapper if not already wrapped
 */
function waklert_wrap_prose_tables($content) {
    if (empty($content) || !is_string($content) || strpos($content, '<table') === false) {
        return $content;
    }
    // Avoid double wrapping if already inside .table-wrapper or wp-block-table
    if (strpos($content, 'table-wrapper') !== false || strpos($content, 'wp-block-table') !== false) {
        return $content;
    }
    return preg_replace('/(<table\b[^>]*>.*?<\/table>)/is', '<div class="table-wrapper">$1</div>', $content);
}
add_filter('the_content', 'waklert_wrap_prose_tables', 20);
add_filter('acf/format_value/type=wysiwyg', 'waklert_wrap_prose_tables', 20);



