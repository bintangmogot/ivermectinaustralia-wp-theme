<?php
/** Editing guidance only. Never hide existing content fields or change saved values. */
defined('ABSPATH') || exit;

function ivermectin_admin_uses_page_modules(): bool {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    return $post_id > 0 && function_exists('have_rows') && have_rows('page_modules', $post_id);
}

add_action('edit_form_after_title', static function (): void {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id) { return; }
    $source = ivermectin_admin_uses_page_modules() ? 'Page Modules' : 'Modules or the main page editor';
    echo '<div class="notice notice-info inline" style="margin:12px 0 0"><p>Current page content source: <strong>'
        . esc_html($source) . '</strong>. All existing editing fields remain available. No content has been migrated automatically.</p></div>';
});
