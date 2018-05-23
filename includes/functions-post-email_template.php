<?php
/**
 * Functions for managing the email Template post type.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


// Register the email post type
add_action( 'init', fqn( 'register_email_template_post_type' ) );

/**
 * Register the email template custom post type.
 *
 * @action init
 *
 * @since 1.6.0
 * @return void
 */
function register_email_template_post_type() {
    $labels = array(
        'name'                  => _x( 'Email Templates', 'Post Type General Name', 'ucare' ),
        'singular_name'         => _x( 'Email Template', 'Post Type Singular Name', 'ucare' ),
        'menu_name'             => __( 'Email Templates', 'ucare' ),
        'archives'              => __( 'Template Archives', 'ucare' ),
        'all_items'             => __( 'All Templates', 'ucare' ),
        'add_new_item'          => __( 'New Template', 'ucare' ),
        'add_new'               => __( 'New Template', 'ucare' ),
        'new_item'              => __( 'New Template', 'ucare' ),
        'edit_item'             => __( 'Edit Template', 'ucare' ),
        'update_item'           => __( 'Update Template', 'ucare' ),
        'view_item'             => __( 'View Template', 'ucare' ),
        'search_items'          => __( 'Search Templates', 'ucare' ),
        'not_found'             => __( 'No templates found', 'ucare' ),
        'not_found_in_trash'    => __( 'No templates found in Trash', 'ucare' ),
        'insert_into_item'      => __( 'Insert into template', 'ucare' ),
        'uploaded_to_this_item' => __( 'Uploaded to this template', 'ucare' ),
        'items_list'            => __( 'Email template list', 'ucare' ),
        'items_list_navigation' => __( 'Email template list navigation', 'ucare' ),
        'filter_items_list'     => __( 'Filter templates list', 'ucare' )
    );
    $args = array(
        'label'                => __( 'Email Template' ),
        'description'          => __( 'Templates for automated support emails' ),
        'labels'               => $labels,
        'supports'             => array( 'editor', 'title' ),
        'hierarchical'         => false,
        'public'               => false,
        'show_ui'              => true,
        'show_in_menu'         => false,
        'menu_position'        => 70,
        'show_in_admin_bar'    => false,
        'show_in_nav_menus'    => false,
        'can_export'           => true,
        'has_archive'          => false,
        'exclude_from_search'  => true,
        'publicly_queryable'   => false,
        'capability_type'      => array( 'email_template', 'email_templates' ),
        'map_meta_cap'         => true
    );

    register_post_type( 'email_template', $args );
}