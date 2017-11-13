<?php

namespace ucare;


function register_category_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Ticket Categories', 'taxonomy general name', 'ucare' ),
        'singular_name'              => _x( 'Ticket Category', 'taxonomy singular name', 'ucare' ),
        'all_items'                  => __( 'All Categories', 'ucare' ),
        'edit_item'                  => __( 'Edit Category', 'ucare' ),
        'view_item'                  => __( 'View Category', 'ucare' ),
        'update_item'                => __( 'Update Category', 'ucare' ),
        'add_new_item'               => __( 'Add New Category', 'ucare' ),
        'new_item_name'              => __( 'New Category', 'ucare' ),
        'parent_item'                => __( 'Parent Category', 'ucare' ),
        'parent_item_colon'          => __( 'Parent Category:', 'ucare' ),
        'search_items'               => __( 'Search Categories', 'ucare' ),
        'popular_items'              => __( 'Popular Categories', 'ucare' ),
        'not_found'                  => __( 'No categories found', 'ucare' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'ucare' ),
        'choose_from_most_used'      => __( 'Choose from the most used categories', 'ucare' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'ucare' )
    );

    $args = array(
        'label'        => __( 'Categories', 'ucare' ),
        'labels'       => $labels,
        'hierarchical' => false
    );

    register_taxonomy( 'ticket_category', 'support_ticket', $args );

}

add_action( 'init', 'ucare\register_category_taxonomy' );

