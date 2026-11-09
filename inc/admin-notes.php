<?php
/**
 * wp-admin helper: on a page, post or product that uses "Page Modules", the previous theme's "Modules" box is
 * ignored by the site, so it is hidden there and a note says where to edit. Pages without Page Modules keep the
 * "Modules" box, which is what the site shows for them. Nothing saved is changed.
 */

defined('ABSPATH') || exit;

/** True when the post being edited has rows in the Page Modules field. */
function ivermectin_admin_uses_page_modules(): bool {
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    return $post_id > 0 && function_exists('have_rows') && have_rows('page_modules', $post_id);
}

add_action('admin_head-post.php', static function (): void {
    if (ivermectin_admin_uses_page_modules()) {
        echo '<style>#acf-group_68adc05fe2e0a { display: none !important; }</style>';
    } else {
        $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
        if ($post_id > 0 && get_post_type($post_id) === 'page' && have_rows('modules', $post_id)) {
            echo '<style>#acf-group_page_modules { display: none !important; }</style>';
        }
    }
});

add_action('edit_form_after_title', static function (): void {
    if (!ivermectin_admin_uses_page_modules()) {
        $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
        if ($post_id > 0 && get_post_type($post_id) === 'page' && have_rows('modules', $post_id)) {
            echo '<div class="notice notice-info inline" style="margin:12px 0 0"><p><strong>Edit this page in the "Modules" box below.</strong> '
                . 'The empty "Page Modules" box is hidden. Existing content is preserved.</p></div>';
        }
        return;
    }
    echo '<div class="notice notice-info inline" style="margin:12px 0 0"><p><strong>Edit this page in the "Page Modules" box below.</strong> '
        . 'The older "Modules" box is ignored on this page, so it is hidden.</p></div>';
});
