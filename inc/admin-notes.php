<?php
/** Editing guidance and controls for fields that have no frontend output. */
defined('ABSPATH') || exit;

// These image fields are not rendered by their respective module templates.
// Keep definitions and saved image IDs intact, but remove misleading controls.
$unused_image_control = static function ($field) {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ($screen && ($screen->post_type ?? '') === 'acf-field-group') { return $field; }
    // Submit the existing attachment ID with the row, so reordering modules
    // keeps it associated with the same content instead of losing it.
    $field['wrapper']['class'] = trim(($field['wrapper']['class'] ?? '') . ' acf-hidden');
    return $field;
};
foreach (['field_iva_rich_text_image', 'field_iva_how_to_order_image', 'field_hero_mobile_image'] as $field_key) {
    add_filter('acf/prepare_field/key=' . $field_key, $unused_image_control);
}

// Restore content panels also hidden through Screen Options during the earlier workaround.
add_filter('hidden_meta_boxes', static function (array $hidden, $screen): array {
    if (!in_array($screen->post_type ?? '', ['page', 'product'], true)) { return $hidden; }
    return array_values(array_filter($hidden, static fn($id) => strpos((string) $id, 'acf-') !== 0));
}, 20, 2);

function ivermectin_admin_uses_page_modules(): bool {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    return $post_id > 0 && function_exists('have_rows') && have_rows('page_modules', $post_id);
}

add_action('edit_form_after_title', static function (): void {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id) { return; }
    $source = ivermectin_admin_uses_page_modules() ? 'Page Modules' : 'Modules or the main page editor';
    echo '<div class="notice notice-info inline" style="margin:12px 0 0"><p>Current page content source: <strong>'
        . esc_html($source) . '</strong>. Existing saved content is retained. No content is migrated automatically.</p></div>';
});
