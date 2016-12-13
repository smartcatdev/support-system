<?php

if( !defined( 'SMARTCAT_MAIL' ) ) :

require_once 'tgm/TGM_Plugin_Activation.php';

add_action( 'tgmpa_register', function () use ( $REQUIRER, $TEXT_DOMAIN ) {

    $plugins = array(
        array(
            'name'      => 'WP SMTP',
            'slug'      => 'wp-smtp',
            'required'  => false
        )
    );

    $config = array(
        'id'           => 'smartcat_required_plugins',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'plugins.php',
        'capability'   => 'manage_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => array(
            'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). */
                $REQUIRER . ' requires the following plugin: %1$s.',
                $REQUIRER . ' requires the following plugins: %1$s.',
				$TEXT_DOMAIN
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). */
                $REQUIRER . ' recommends the following plugin: %1$s.',
                $REQUIRER . ' recommends the following plugins: %1$s.',
				$TEXT_DOMAIN
			),
        )
    );

    tgmpa( $plugins, $config );
} );


//configure cpt


define( 'SMARTCAT_MAIL', true );

endif;



//add_action( 'init', function () {
//    $labels = array(
//        'name'                  => _x( 'Email Templates', 'Post Type General Name' ),
//        'singular_name'         => _x( 'Email Template', 'Post Type Singular Name' ),
//        'menu_name'             => __( 'Email' ),
//        'name_admin_bar'        => __( 'Email' ),
//        'archives'              => __( 'Email Template Archives' ),
//        'parent_item_colon'     => __( 'Parent Item:' ),
//        'all_items'             => __( 'All Templates' ),
//        'add_new_item'          => __( 'New Template' ),
//        'add_new'               => __( 'New Template' ),
//        'new_item'              => __( 'New Template' ),
//        'edit_item'             => __( 'Edit Template' ),
//        'update_item'           => __( 'Update Template' ),
//        'view_item'             => __( 'View Template' ),
//        'search_items'          => __( 'Search Templates' ),
//        'not_found'             => __( 'Template Not found' ),
//        'not_found_in_trash'    => __( 'Template Not found in Trash' ),
//        'featured_image'        => __( 'Featured Image' ),
//        'set_featured_image'    => __( 'Set featured image' ),
//        'remove_featured_image' => __( 'Remove featured image' ),
//        'use_featured_image'    => __( 'Use as featured image' ),
//        'insert_into_item'      => __( 'Insert into template' ),
//        'uploaded_to_this_item' => __( 'Uploaded to this template' ),
//        'items_list'            => __( 'Templates list' ),
//        'items_list_navigation' => __( 'Templates list navigation' ),
//        'filter_items_list'     => __( 'Filter templates list' )
//    );
//
//    $capabilities = array();
//
//    $args = array(
//        'label'               => __( 'Email Template' ),
//        'description'         => __( 'Templates for automated emails' ),
//        'labels'              => $labels,
//        'supports'            => array( 'editor', 'author', 'comments', 'title' ),
//        'hierarchical'        => false,
//        'public'              => false,
//        'show_ui'             => true,
//        'show_in_menu'        => true,
//        'menu_position'       => 70,
//        'menu_icon'           => 'dashicons-email-alt',
//        'show_in_admin_bar'   => true,
//        'show_in_nav_menus'   => true,
//        'can_export'          => true,
//        'has_archive'         => false,
//        'exclude_from_search' => true,
//        'publicly_queryable'  => false,
//        'capabilities'        => $capabilities
//    );
//
//    register_post_type( 'email', $args );
//} );
//
