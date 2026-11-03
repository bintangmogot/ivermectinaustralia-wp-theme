<?php
/**
 * Handles moderated product review submissions from the product page.
 */

function ivermectin_submit_product_review_ajax() {
    $nonce = isset($_POST['review_nonce']) ? sanitize_text_field(wp_unslash($_POST['review_nonce'])) : '';
    if (!$nonce || !wp_verify_nonce($nonce, 'ivermectin_submit_product_review')) {
        wp_send_json_error(['message' => ivermectin_label('product_reviews_msg_session')], 403);
    }

    $product_id = isset($_POST['product_id']) ? absint(wp_unslash($_POST['product_id'])) : 0;
    if (!$product_id || get_post_type($product_id) !== 'product' || get_post_status($product_id) !== 'publish') {
        wp_send_json_error(['message' => ivermectin_label('product_reviews_msg_unavailable')], 400);
    }

    $rating = isset($_POST['rating']) ? absint(wp_unslash($_POST['rating'])) : 0;
    $name = isset($_POST['reviewer_name']) ? sanitize_text_field(wp_unslash($_POST['reviewer_name'])) : '';
    $email = isset($_POST['reviewer_email']) ? sanitize_email(wp_unslash($_POST['reviewer_email'])) : '';
    $title = isset($_POST['review_title']) ? sanitize_text_field(wp_unslash($_POST['review_title'])) : '';
    $content = isset($_POST['review_content']) ? sanitize_textarea_field(wp_unslash($_POST['review_content'])) : '';

    if ($rating < 1 || $rating > 5 || !$name || !$title || !$content || !is_email($email)) {
        wp_send_json_error(['message' => ivermectin_label('product_reviews_msg_incomplete')], 400);
    }

    $review_id = wp_insert_post([
        'post_type' => 'reviews',
        'post_status' => 'draft',
        'post_title' => $title,
        'post_content' => $content,
    ], true);

    if (is_wp_error($review_id)) {
        wp_send_json_error(['message' => ivermectin_label('product_reviews_msg_save_failed')], 500);
    }

    $review_fields = [
        'rating' => $rating,
        'name' => $name,
        'email' => $email,
        'linked_product' => $product_id,
    ];

    foreach ($review_fields as $field_name => $field_value) {
        if (function_exists('update_field')) {
            update_field($field_name, $field_value, $review_id);
        } else {
            update_post_meta($review_id, $field_name, $field_value);
        }
    }
    update_post_meta($review_id, '_reviewer_email', $email);

    wp_send_json_success(['message' => ivermectin_label('product_reviews_msg_thanks')]);
}

add_action('wp_ajax_ivermectin_submit_product_review', 'ivermectin_submit_product_review_ajax');
add_action('wp_ajax_nopriv_ivermectin_submit_product_review', 'ivermectin_submit_product_review_ajax');
