<?php
/**
 * Custom Post Types
 */

function modmy_register_post_types() {
    // 1. Reviews CPT
    $review_labels = array(
        'name'                  => 'Reviews',
        'singular_name'         => 'Review',
        'menu_name'             => 'Reviews',
        'name_admin_bar'        => 'Review',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Review',
        'new_item'              => 'New Review',
        'edit_item'             => 'Edit Review',
        'view_item'             => 'View Review',
        'all_items'             => 'All Reviews',
        'search_items'          => 'Search Reviews',
        'not_found'             => 'No reviews found.',
    );

    $review_args = array(
        'labels'             => $review_labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'review' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-star-filled',
        'supports'           => array( 'title', 'editor' ),
    );

    register_post_type( 'review', $review_args );

    // 2. Cities CPT
    $city_labels = array(
        'name'                  => 'Australia Delivery',
        'singular_name'         => 'City',
        'menu_name'             => 'Australia Delivery',
        'name_admin_bar'        => 'City',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New City',
        'new_item'              => 'New City',
        'edit_item'             => 'Edit City',
        'view_item'             => 'View City',
        'all_items'             => 'All Cities',
        'search_items'          => 'Search Cities',
        'not_found'             => 'No cities found.',
    );

    $city_args = array(
        'labels'             => $city_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'buy-modafinil', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-location',
        'supports'           => array( 'title', 'thumbnail' ),
    );

    register_post_type( 'city', $city_args );
}
add_action( 'init', 'modmy_register_post_types' );
