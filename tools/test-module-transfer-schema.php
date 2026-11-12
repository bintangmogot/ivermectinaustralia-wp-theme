<?php
/** Standalone regression check for additive ACF schema merging. */

require_once __DIR__ . '/../inc/module-transfer-schema.php';

$existing_layout = [
    'key' => 'layout_video',
    'name' => 'previous__video',
    'label' => 'Existing label',
    'sub_fields' => [
        ['key' => 'field_video', 'name' => 'video', 'type' => 'file', 'instructions' => 'Keep the saved video editor settings.'],
        ['key' => 'field_content', 'name' => 'content', 'type' => 'wysiwyg', 'toolbar' => 'basic'],
    ],
];
$incoming_layout = [
    'key' => 'layout_video',
    'name' => 'previous__video',
    'label' => 'New label must not replace the existing label',
    'sub_fields' => [
        ['key' => 'field_video', 'name' => 'video', 'type' => 'file', 'instructions' => 'Different instructions must not replace the existing definition.'],
        ['key' => 'field_content', 'name' => 'content', 'type' => 'wysiwyg', 'toolbar' => 'full'],
        ['key' => 'field_poster', 'name' => 'poster_url', 'type' => 'url', 'default_value' => ''],
    ],
];

$merged = iva_transfer_merge_layout_definitions(
    ['layout_video' => $existing_layout],
    ['layout_video' => $incoming_layout]
);
$layout = $merged['layout_video'] ?? null;
if (!$layout || $layout['label'] !== 'Existing label' || count($layout['sub_fields']) !== 3) {
    throw new RuntimeException('The merge replaced an existing layout or failed to add its new subfield.');
}
if ($layout['sub_fields'][0] !== $existing_layout['sub_fields'][0] || $layout['sub_fields'][1] !== $existing_layout['sub_fields'][1]) {
    throw new RuntimeException('An existing subfield definition was changed.');
}
if (($layout['sub_fields'][2]['key'] ?? '') !== 'field_poster') {
    throw new RuntimeException('The missing poster field was not appended.');
}

$conflict = false;
try {
    iva_transfer_merge_field_definitions(
        [['key' => 'field_old', 'name' => 'content', 'type' => 'wysiwyg']],
        [['key' => 'field_new', 'name' => 'content', 'type' => 'wysiwyg']]
    );
} catch (RuntimeException $error) {
    $conflict = str_contains($error->getMessage(), 'collides with a different key');
}
if (!$conflict) {
    throw new RuntimeException('The merge did not reject a same-name/different-key collision.');
}

echo "Additive schema merge checks passed.\n";
