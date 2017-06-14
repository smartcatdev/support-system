<?php

namespace smartcat\mail;


function init() {
    $metabox = new TemplateStyleMetaBox( array(
        'id'        => 'mailer_meta',
        'title'     => __( 'Template Style Sheet' ),
        'post_type' => 'email_template',
        'context'   => 'advanced',
        'priority'  => 'high'
    ) );

    add_caps();
}

add_action( 'plugins_loaded', 'smartcat\mail\init' );

function register_template_post_type() {
    $labels = array(
        'name'                  => _x( 'Email Templates', 'Post Type General Name' ),
        'singular_name'         => _x( 'Email Template', 'Post Type Singular Name' ),
        'menu_name'             => __( 'Email Templates' ),
        'name_admin_bar'        => __( 'Email Templates' ),
        'archives'              => __( 'Template Archives' ),
        'parent_item_colon'     => __( 'Parent Item:' ),
        'all_items'             => __( 'All Templates' ),
        'add_new_item'          => __( 'New Template' ),
        'add_new'               => __( 'New Template' ),
        'new_item'              => __( 'New Template' ),
        'edit_item'             => __( 'Edit Template' ),
        'update_item'           => __( 'Update Template' ),
        'view_item'             => __( 'View Template' ),
        'search_items'          => __( 'Search Templates' ),
        'not_found'             => __( 'No templates found' ),
        'not_found_in_trash'    => __( 'No templates found in Trash' ),
        'featured_image'        => __( 'Featured Image' ),
        'set_featured_image'    => __( 'Set featured image' ),
        'remove_featured_image' => __( 'Remove featured image' ),
        'use_featured_image'    => __( 'Use as featured image' ),
        'insert_into_item'      => __( 'Insert into template' ),
        'uploaded_to_this_item' => __( 'Uploaded to this template' ),
        'items_list'            => __( 'Templates list' ),
        'items_list_navigation' => __( 'Templates list navigation' ),
        'filter_items_list'     => __( 'Filter templates list' )
    );

    $args = array(
        'label'               => __( 'Email Template' ),
        'description'         => __( 'Templates for automated emails' ),
        'labels'              => $labels,
        'supports'            => array( 'editor', 'title' ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 70,
        'menu_icon'           => 'dashicons-email-alt',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'email_template',
        'map_meta_cap'        => true
    );
    //</editor-fold>

    register_post_type( 'email_template', $args );

}

add_action( 'init', 'smartcat\mail\register_template_post_type' );


function disable_wsiwyg( $enabled ) {
    if( get_post_type() == 'email_template' ) {
        $enabled = false;
    }

    return $enabled;
}

add_filter( 'user_can_richedit', 'smartcat\mail\disable_wsiwyg' );