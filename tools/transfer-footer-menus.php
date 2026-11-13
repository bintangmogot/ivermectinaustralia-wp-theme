<?php
/** Explicitly copy the existing footer groups to WordPress menus. Default: preview. */
if (PHP_SAPI !== 'cli' || !defined('ABSPATH')) { exit("Use WP-CLI eval-file.\n"); }
if (!function_exists('get_field') || !function_exists('ivermectin_parse_footer_links')) { throw new RuntimeException('Theme and ACF must be active.'); }
$mode = $args[0] ?? 'preview';
if (!in_array($mode, ['preview', 'apply'], true)) { throw new RuntimeException('Use preview or apply.'); }
$mapping = [
    'footer_menu' => ['footer_trending'],
    'footer_menu_2' => ['footer_quick', 'footer_cities'],
    'footer_menu_3' => ['footer_info'],
];
$existing = get_nav_menu_locations();
$plan = [];
$errors = [];
$source_values = [];
foreach ($mapping as $field => $slots) {
    $source_values[$field] = (string) get_field($field, 'option');
    $groups = ivermectin_parse_footer_links($source_values[$field]);
    if (count($groups) > count($slots)) { $errors[] = "$field contains additional groups; review them before transfer."; }
    foreach ($slots as $index => $location) {
        if (!empty($existing[$location])) { continue; }
        if (!isset($groups[$index])) { $errors[] = "$location has no matching saved footer group."; continue; }
        $group = $groups[$index];
        if ($group['title'] === '' || !$group['links']) { $errors[] = "$location has an empty title or links."; continue; }
        if (wp_get_nav_menu_object($group['title'])) { $errors[] = "A menu named {$group['title']} already exists; assign or review it manually."; continue; }
        $plan[$location] = $group;
    }
}
$summary = [];
foreach ($plan as $location => $group) { $summary[$location] = ['title' => $group['title'], 'items' => count($group['links'])]; }
echo wp_json_encode(['mode' => $mode, 'site' => home_url('/'), 'menus_to_create' => $summary, 'conflicts' => $errors], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
if ($mode === 'preview') { return; }
if (!current_user_can('manage_options')) { throw new RuntimeException('Run with WP-CLI --user=<administrator>.'); }
if ($errors) { throw new RuntimeException('Preflight conflicts: no menus were created.'); }
$created = [];
foreach ($plan as $location => $group) {
    $menu_id = wp_create_nav_menu($group['title']);
    if (is_wp_error($menu_id)) { throw new RuntimeException($menu_id->get_error_message()); }
    $created[$location] = (int) $menu_id;
    foreach ($group['links'] as $link) {
        $id = wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $link['label'], 'menu-item-url' => $link['href'],
            'menu-item-type' => 'custom', 'menu-item-status' => 'publish',
            'menu-item-target' => $link['target'], 'menu-item-xfn' => $link['rel'],
        ]);
        if (is_wp_error($id) || !$id) { throw new RuntimeException("Could not create an item in {$group['title']}. The new menu remains unassigned; review it before retrying."); }
    }
    $items = wp_get_nav_menu_items($menu_id);
    if (!is_array($items) || count($items) !== count($group['links'])) { throw new RuntimeException("Menu {$group['title']} did not read back correctly; it remains unassigned."); }
    foreach ($group['links'] as $i => $link) {
        if ($items[$i]->title !== $link['label'] || $items[$i]->url !== $link['href'] || $items[$i]->target !== $link['target'] || $items[$i]->xfn !== $link['rel']) {
            throw new RuntimeException("Menu {$group['title']} differs from the saved links; it remains unassigned.");
        }
    }
}
if (get_nav_menu_locations() !== $existing) { throw new RuntimeException('Menu assignments changed during transfer; created menus remain unassigned.'); }
foreach ($source_values as $field => $value) {
    if ((string) get_field($field, 'option') !== $value) { throw new RuntimeException('Saved footer content changed during transfer; created menus remain unassigned.'); }
}
if ($created) {
    set_theme_mod('nav_menu_locations', array_merge($existing, $created));
    $assigned = get_nav_menu_locations();
    foreach ($created as $location => $id) {
        if ((int) ($assigned[$location] ?? 0) !== $id) { throw new RuntimeException("Could not verify the assignment for $location; review the menu locations."); }
    }
}
echo "Footer menus created and verified. Original footer fields remain unchanged.\n";
