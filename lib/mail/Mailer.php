<?php

namespace smartcat\mail;

use smartcat\core\HookRegisterer;
use smartcat\core\HookSubscriber;

if( !class_exists( '\smartcat\mail\Mailer' ) ) :

class Mailer implements HookSubscriber  {

    private static $instance;

    public static function init( HookRegisterer $plugin ) {
        if( empty( self::$instance ) ) {
            self::$instance = new self();
            $plugin->add_api_subscriber( self::$instance );
        }

        return self::$instance;
    }

    public function replace_default_vars( $content, $recipient ) {
        $user = get_user_by( 'email', $recipient );

        if( !empty( $user ) ) {
            $content = str_replace( '{%username%}', $user->user_login, $content );
            $content = str_replace( '{%first_name%}', $user->first_name, $content );
            $content = str_replace( '{%last_name%}', $user->last_name, $content );
            $content = str_replace( '{%full_name%}', $user->first_name . ' ' . $user->last_name, $content );
        }

        $content = str_replace( '{%email%}', !empty( $user ) ? $user->user_email : $recipient, $content );

        return $content;
    }

    public function register_template_cpt() {
        $text_domain = apply_filters( 'mailer_text_domain', '' );

        //<editor-fold desc="$args array">
        $labels = array(
            'name'                  => _x( 'Email Templates', 'Post Type General Name', $text_domain ),
            'singular_name'         => _x( 'Email Template', 'Post Type Singular Name', $text_domain ),
            'menu_name'             => __( 'Email Templates', $text_domain ),
            'name_admin_bar'        => __( 'Email Templates', $text_domain ),
            'archives'              => __( 'Template Archives', $text_domain ),
            'parent_item_colon'     => __( 'Parent Item:', $text_domain ),
            'all_items'             => __( 'All Templates', $text_domain ),
            'add_new_item'          => __( 'New Template', $text_domain ),
            'add_new'               => __( 'New Template', $text_domain ),
            'new_item'              => __( 'New Template', $text_domain ),
            'edit_item'             => __( 'Edit Template', $text_domain ),
            'update_item'           => __( 'Update Template', $text_domain ),
            'view_item'             => __( 'View Template', $text_domain ),
            'search_items'          => __( 'Search Templates', $text_domain ),
            'not_found'             => __( 'No templates found', $text_domain ),
            'not_found_in_trash'    => __( 'No templates found in Trash', $text_domain ),
            'featured_image'        => __( 'Featured Image', $text_domain ),
            'set_featured_image'    => __( 'Set featured image', $text_domain ),
            'remove_featured_image' => __( 'Remove featured image', $text_domain ),
            'use_featured_image'    => __( 'Use as featured image', $text_domain ),
            'insert_into_item'      => __( 'Insert into template', $text_domain ),
            'uploaded_to_this_item' => __( 'Uploaded to this template', $text_domain ),
            'items_list'            => __( 'Templates list', $text_domain ),
            'items_list_navigation' => __( 'Templates list navigation', $text_domain ),
            'filter_items_list'     => __( 'Filter templates list', $text_domain )
        );

        $args = array(
            'label'               => __( 'Email Template', $text_domain ),
            'description'         => __( 'Templates for automated emails', $text_domain ),
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

    public static function send_template( $template_id, $recipient ) {
        $template = get_post( $template_id );
        $sent = false;

        if( !empty( $template ) ) {
            $sent = wp_mail(
                $recipient,
                $template->post_title,
                apply_filters( 'parse_email_template', $template->post_content, $recipient, $template->ID ),
                array( 'Content-Type: text/html; charset=UTF-8' )
            );
        }

        return $sent;
    }

    public static function cleanup( $nuke = false ) {
        if( empty( apply_filters( 'mailer_consumers', array() ) ) ) {
            unregister_post_type( 'email_template' );

            if( $nuke ) {
                $query = new \WP_Query( array( 'post_type' => 'email_template' ) );

                foreach( $query->posts as $post ) {
                    wp_trash_post( $post->ID );
                }
            }
        }
    }

    public function subscribed_hooks() {
        return array(
            'init' => array( 'register_template_cpt' ),
            'parse_email_template' => array( 'replace_default_vars', 10, 3 )
        );
    }

    public static function list_templates() {
        $templates = array();

        $query = new \WP_Query(
            array(
                'post_type'   => 'email_template',
                'post_status' => 'publish'
            )
        );

        if( $query->have_posts() ) {
            foreach( $query->posts as $template ) {
                $templates [ $template->ID ] = $template->post_title;
            }
        }

        return $templates;
    }

}

endif;

