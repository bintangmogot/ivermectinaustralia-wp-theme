<?php
/** Lossless schema-only merges used by the explicit ACF module transfer command. */

/** Add definitions that are missing while retaining all existing field settings and data. */
function iva_transfer_merge_field_definitions(array $existing, array $incoming): array {
    foreach ($incoming as $new_field) {
        $match_key = null;
        foreach ($existing as $key => $old_field) {
            if (($old_field['key'] ?? '') === ($new_field['key'] ?? '')) {
                $match_key = $key;
                break;
            }
            if (!empty($new_field['name']) && ($old_field['name'] ?? '') === $new_field['name']) {
                throw new RuntimeException('ACF field name collides with a different key: ' . $new_field['name']);
            }
        }

        if ($match_key === null) {
            $existing[] = $new_field;
            continue;
        }

        $old_field = $existing[$match_key];
        foreach (['key', 'name', 'type'] as $property) {
            if (($old_field[$property] ?? null) !== ($new_field[$property] ?? null)) {
                throw new RuntimeException('ACF field definition mismatch for key: ' . ($new_field['key'] ?? 'unknown'));
            }
        }

        if (isset($new_field['sub_fields'])) {
            $old_field['sub_fields'] = iva_transfer_merge_field_definitions(
                (array) ($old_field['sub_fields'] ?? []),
                (array) $new_field['sub_fields']
            );
        }
        if (isset($new_field['layouts'])) {
            $old_field['layouts'] = iva_transfer_merge_layout_definitions(
                (array) ($old_field['layouts'] ?? []),
                (array) $new_field['layouts']
            );
        }
        $existing[$match_key] = $old_field;
    }

    return array_values($existing);
}

/** Merge flexible-content layouts by stable ACF key and name. */
function iva_transfer_merge_layout_definitions(array $existing, array $incoming): array {
    foreach ($incoming as $new_layout) {
        $match_key = null;
        foreach ($existing as $key => $old_layout) {
            if (($old_layout['name'] ?? '') === ($new_layout['name'] ?? '')) {
                if (($old_layout['key'] ?? '') !== ($new_layout['key'] ?? '')) {
                    throw new RuntimeException('Imported layout name collides with a different field key: ' . ($new_layout['name'] ?? 'unknown'));
                }
                $match_key = $key;
                break;
            }
            if (($old_layout['key'] ?? '') === ($new_layout['key'] ?? '')) {
                throw new RuntimeException('Imported layout key collides with a different name: ' . ($new_layout['key'] ?? 'unknown'));
            }
        }

        if ($match_key === null) {
            $existing[$new_layout['key']] = $new_layout;
            continue;
        }

        $old_layout = $existing[$match_key];
        $old_layout['sub_fields'] = iva_transfer_merge_field_definitions(
            (array) ($old_layout['sub_fields'] ?? []),
            (array) ($new_layout['sub_fields'] ?? [])
        );
        $existing[$match_key] = $old_layout;
    }

    return $existing;
}
