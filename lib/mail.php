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

add_action( 'init', function () use ( $TEXT_DOMAIN ) {

    $labels = array(
        'name'                  => _x( 'Email Templates', 'Post Type General Name', $TEXT_DOMAIN ),
        'singular_name'         => _x( 'Email Template', 'Post Type Singular Name', $TEXT_DOMAIN ),
        'menu_name'             => __( 'Email Templates', $TEXT_DOMAIN ),
        'name_admin_bar'        => __( 'Email Templates', $TEXT_DOMAIN ),
        'archives'              => __( 'Template Archives', $TEXT_DOMAIN ),
        'parent_item_colon'     => __( 'Parent Item:', $TEXT_DOMAIN ),
        'all_items'             => __( 'All Templates', $TEXT_DOMAIN ),
        'add_new_item'          => __( 'New Template', $TEXT_DOMAIN ),
        'add_new'               => __( 'New Template', $TEXT_DOMAIN ),
        'new_item'              => __( 'New Template', $TEXT_DOMAIN ),
        'edit_item'             => __( 'Edit Template', $TEXT_DOMAIN ),
        'update_item'           => __( 'Update Template', $TEXT_DOMAIN ),
        'view_item'             => __( 'View Template', $TEXT_DOMAIN ),
        'search_items'          => __( 'Search Templates', $TEXT_DOMAIN ),
        'not_found'             => __( 'Template Not found', $TEXT_DOMAIN ),
        'not_found_in_trash'    => __( 'Template Not found in Trash', $TEXT_DOMAIN ),
        'featured_image'        => __( 'Featured Image', $TEXT_DOMAIN ),
        'set_featured_image'    => __( 'Set featured image', $TEXT_DOMAIN ),
        'remove_featured_image' => __( 'Remove featured image', $TEXT_DOMAIN ),
        'use_featured_image'    => __( 'Use as featured image', $TEXT_DOMAIN ),
        'insert_into_item'      => __( 'Insert into template', $TEXT_DOMAIN ),
        'uploaded_to_this_item' => __( 'Uploaded to this template', $TEXT_DOMAIN ),
        'items_list'            => __( 'Templates list', $TEXT_DOMAIN ),
        'items_list_navigation' => __( 'Templates list navigation', $TEXT_DOMAIN ),
        'filter_items_list'     => __( 'Filter templates list', $TEXT_DOMAIN )
    );

    $capabilities = array();

    $args = array(
        'label'               => __( 'Email Template', $TEXT_DOMAIN ),
        'description'         => __( 'Templates for automated emails', $TEXT_DOMAIN ),
        'labels'              => $labels,
        'supports'            => array( 'editor', 'author', 'comments', 'title' ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 70,
        'menu_icon'           => 'dashicons-email-alt',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capabilities'        => $capabilities
    );

    register_post_type( 'email_template', $args );
} );



add_action( 'smartcat_send_mail', function( $template_tag, $recipient ) {

    $query = new \WP_Query(
        array(
            'name'      => $template_tag,
            'post_type' => 'email_template'
        )
    );

    if( $query->have_posts() ) {
        $post = $query->post;

        wp_mail(
            $recipient,
            $post->post_title,
            $post->post_content,
            array( 'Content-Type: text/html; charset=UTF-8' )
        );
    }

}, 10, 2 );

define( 'SMARTCAT_MAIL', true );

endif;
