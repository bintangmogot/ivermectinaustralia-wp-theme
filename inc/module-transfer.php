<?php
/** Rendering compatibility for sections explicitly transferred to Page Modules. */
defined('ABSPATH') || exit;

function ivermectin_original_module_layout(string $layout): string {
    return str_starts_with($layout, 'previous__') ? substr($layout, 10) : $layout;
}

function ivermectin_uses_transferred_modules($post_id = false): bool {
    $rows = get_post_meta($post_id ?: get_the_ID(), 'page_modules', true);
    if (!is_array($rows) || !$rows) { return false; }
    foreach ($rows as $layout) {
        if (!is_string($layout) || !str_starts_with($layout, 'previous__')) { return false; }
    }
    return true;
}

/** Supports transferred sections alongside newly added Page Modules rows. */
function ivermectin_render_page_module(string $layout): void {
    if (!str_starts_with($layout, 'previous__')) {
        get_template_part('modules/content', $layout);
        return;
    }
    $row = ivermectin_current_legacy_row();
    if ($row !== null) {
        $fn = 'ivermectin_legacy_render_' . preg_replace('/[^a-z0-9]+/', '_', $row['layout']);
        if (function_exists($fn)) { $fn($row, get_row_index() - 1); }
        return;
    }
    global $post, $product;
    $iva_legacy_field = 'page_modules';
    $iva_legacy_single = true;
    echo '<div class="iva-legacy">';
    include get_theme_file_path('legacy/modules.php');
    echo '</div>';
}
