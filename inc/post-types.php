<?php
/**
 * Custom Post Types.
 *
 * "reviews" and "reviews_cat" keep the names used by the previous theme so existing
 * reviews stay available after the theme is switched.
 */

function ivermectin_register_post_types() {
    register_post_type('reviews', array(
        'label'           => __('Reviews', 'ivermectin-australia'),
        'singular_label'  => __('Review', 'ivermectin-australia'),
        'public'          => false,
        'show_ui'         => true,
        'capability_type' => 'post',
        'hierarchical'    => false,
        'has_archive'     => false,
        'rewrite'         => true,
        'query_var'       => true,
        'menu_position'   => 20,
        'menu_icon'       => 'dashicons-star-filled',
        'supports'        => array('title', 'editor'),
    ));

    register_taxonomy('reviews_cat', array('reviews'), array(
        'hierarchical'      => true,
        'labels'            => array('name' => _x('Reviews Categories', 'taxonomy general name')),
        'show_ui'           => true,
        'query_var'         => true,
        'show_admin_column' => true,
        'public'            => false,
    ));

    register_post_type('city', array(
        'labels' => array(
            'name'          => 'Delivery Areas',
            'singular_name' => 'Delivery Area',
            'menu_name'     => 'Delivery Areas',
            'add_new_item'  => 'Add New Delivery Area',
            'edit_item'     => 'Edit Delivery Area',
            'all_items'     => 'All Delivery Areas',
            'search_items'  => 'Search Delivery Areas',
            'not_found'     => 'No delivery areas found.',
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'delivery-areas', 'with_front' => false),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-location',
        'supports'           => array('title', 'thumbnail'),
    ));
}
add_action('init', 'ivermectin_register_post_types');
