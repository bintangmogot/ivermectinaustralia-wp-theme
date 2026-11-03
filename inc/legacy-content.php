<?php
/**
 * Existing site content.
 *
 * The previous theme stored page content in an ACF flexible field called "modules", options
 * such as footer_text and payid, and product fields such as short_description. None of that
 * data is touched. This file only reads it, so it keeps showing under the new design.
 * New pages use the "page_modules" field.
 */

defined('ABSPATH') || exit;

/**
 * Render the modules for a post. Returns true when something was rendered.
 * New "page_modules" win. Otherwise the previous theme's "modules" are rendered in the current design
 * (inc/legacy-adapters.php), or with the old markup when a layout has no adapter. $opts is passed to the adapters.
 */
function ivermectin_render_modules($post_id = false, array $opts = []) {
    if (have_rows('page_modules', $post_id)) {
        while (have_rows('page_modules', $post_id)) {
            the_row();
            get_template_part('modules/content', get_row_layout());
        }
        return true;
    }

    if (have_rows('modules', $post_id)) {
        if (ivermectin_render_legacy_modules($post_id, $opts)) {
            return true;
        }
        // A layout without an adapter: the old markup keeps the content visible. The old template ran in global scope and loops like "foreach (... as $post)" relied on it.
        global $post, $product;
        echo '<div class="iva-legacy">';
        include get_theme_file_path('legacy/modules.php');
        echo '</div>';
        return true;
    }

    return false;
}

/** PayID from Theme Settings, then from the previous theme's options field. */
function ivermectin_get_payid() {
    $value = function_exists('get_field') ? get_field('payid_number', 'option') : '';
    if (!$value && function_exists('get_field')) {
        $value = get_field('payid', 'option');
    }
    return trim((string) $value);
}

/** Footer text from Theme Settings, then from the previous theme's options field. */
function ivermectin_get_footer_description() {
    $value = function_exists('get_field') ? get_field('footer_description', 'option') : '';
    if (!$value && function_exists('get_field')) {
        $value = get_field('footer_text', 'option');
    }
    return (string) $value;
}
