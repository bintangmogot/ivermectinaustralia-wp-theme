<?php
/**
 * Delivery areas: loads the remaining PHP-managed ACF groups.
 * Page Modules uses native ACF Local JSON instead: it can be synced into the
 * database and edited in Field Groups, with saves exported by theme-options.php.
 * Does not create or edit any posts.
 */
defined('ABSPATH') || exit;

add_action('acf/init', static function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    foreach (['group_city_fields', 'group_theme_settings'] as $key) {
        $path = get_theme_file_path("/acf-json/{$key}.json");
        if (file_exists($path)) {
            $group = json_decode(file_get_contents($path), true);
            if (is_array($group) && !empty($group['key'])) {
                acf_add_local_field_group($group);
            }
        }
    }
});
