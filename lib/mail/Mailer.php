<?php

namespace smartcat\mail;

use smartcat\core\AbstractPlugin;
use smartcat\core\HookSubscriber;

if( !class_exists( '\smartcat\mail\Mailer' ) ) :

class Mailer implements HookSubscriber  {

    private $consumers = array();
    private $text_domain = '';

    private static $instance;

    public static function init( AbstractPlugin $plugin ) {
        if( empty( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->text_domain = $plugin->id();
            $plugin->add_api_subscriber( self::$instance );
        }

        self::$instance->consumers[] = $plugin;

        return self::$instance;
    }

    public function replace_default_vars( $content, $recipient, $id ) {
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
        //<editor-fold desc="$args array">
        $labels = array(
            'name'                  => _x( 'Email Templates', 'Post Type General Name', $this->text_domain ),
            'singular_name'         => _x( 'Email Template', 'Post Type Singular Name', $this->text_domain ),
            'menu_name'             => __( 'Email Templates', $this->text_domain ),
            'name_admin_bar'        => __( 'Email Templates', $this->text_domain ),
            'archives'              => __( 'Template Archives', $this->text_domain ),
            'parent_item_colon'     => __( 'Parent Item:', $this->text_domain ),
            'all_items'             => __( 'All Templates', $this->text_domain ),
            'add_new_item'          => __( 'New Template', $this->text_domain ),
            'add_new'               => __( 'New Template', $this->text_domain ),
            'new_item'              => __( 'New Template', $this->text_domain ),
            'edit_item'             => __( 'Edit Template', $this->text_domain ),
            'update_item'           => __( 'Update Template', $this->text_domain ),
            'view_item'             => __( 'View Template', $this->text_domain ),
            'search_items'          => __( 'Search Templates', $this->text_domain ),
            'not_found'             => __( 'No templates found', $this->text_domain ),
            'not_found_in_trash'    => __( 'No templates found in Trash', $this->text_domain ),
            'featured_image'        => __( 'Featured Image', $this->text_domain ),
            'set_featured_image'    => __( 'Set featured image', $this->text_domain ),
            'remove_featured_image' => __( 'Remove featured image', $this->text_domain ),
            'use_featured_image'    => __( 'Use as featured image', $this->text_domain ),
            'insert_into_item'      => __( 'Insert into template', $this->text_domain ),
            'uploaded_to_this_item' => __( 'Uploaded to this template', $this->text_domain ),
            'items_list'            => __( 'Templates list', $this->text_domain ),
            'items_list_navigation' => __( 'Templates list navigation', $this->text_domain ),
            'filter_items_list'     => __( 'Filter templates list', $this->text_domain )
        );

        $capabilities = array();

        $args = array(
            'label'               => __( 'Email Template', $this->text_domain ),
            'description'         => __( 'Templates for automated emails', $this->text_domain ),
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
            'capabilities'        => $capabilities
        );
        //</editor-fold>

        register_post_type( 'email_template', $args );
    }

    public function send_template( $template_id, $recipient ) {
        $template = get_post( $template_id );

        if( !empty( $template ) ) {
            $content = apply_filters( 'pre_parse_email_template', $template->post_content, $recipient,  $template->ID );

            $sent = wp_mail(
                $recipient,
                $template->post_title,
                apply_filters( 'parse_email_template', $content, $recipient, $template->ID ),
                array( 'Content-Type: text/html; charset=UTF-8' )
            );

            do_action( 'post_email_template_sent', $template->ID, $sent );
        }
    }

    public function subscribed_hooks() {
        return array(
            'init' => array( 'register_template_cpt' ),
            'send_email_template' => array( 'send_template', 10, 2 ),
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

