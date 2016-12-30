<?php

namespace SmartcatSupport;

use smartcat\core\AbstractPlugin;
use smartcat\core\HookSubscriber;
use SmartcatSupport\component\ProductComponent;
use SmartcatSupport\component\RegistrationComponent;
use SmartcatSupport\component\TicketCptComponent;
use SmartcatSupport\descriptor\Option;

class Plugin extends AbstractPlugin implements HookSubscriber {

    public function start() {
        $this->add_api_subscriber( $this );

        $this->woo_active = class_exists( 'WooCommerce' );
        $this->edd_active = class_exists( 'Easy_Digital_Downloads' );

        // Notify subscribers if there is a version upgrade
        $version = get_option( Option::PLUGIN_VERSION, 0 );

        if( $this->version > $version ) {
            do_action( $this->name . '_upgrade', $version, $this->version );
            update_option( Option::PLUGIN_VERSION, $this->version );
        }
    }

    public function activate() {
        do_action( $this->name . '_setup' );

        $this->add_caps( add_role( 'support_admin', __( 'Support Admin', PLUGIN_NAME ) ), true );
        $this->add_caps( add_role( 'support_agent', __( 'Support Agent', PLUGIN_NAME ) ), true );
        $this->add_caps( add_role( 'support_user', __( 'Support User', PLUGIN_NAME ) ) );
        $this->add_caps( get_role( 'administrator'), true );

        $this->setup_template();
        $this->create_email_templates();
    }

    public function deactivate() {
        $this->remove_caps( get_role( 'administrator'), true );

        remove_role( 'support_admin' );
        remove_role( 'support_agent' );
        remove_role( 'support_user' );

        do_action( $this->name . '_cleanup' );
    }

    public function admin_enqueue() {
        wp_enqueue_media();
        wp_enqueue_script( 'wp_media_uploader',
            $this->url . 'assets/lib/wp_media_uploader.js', array( 'jquery' ), $this->version );

        wp_register_script( 'support-admin-js',
            $this->url . 'assets/admin/admin.js', array( 'jquery' ), $this->version );

        wp_localize_script( 'support-admin-js', 'SupportSystem', array( 'ajaxURL' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script( 'support-admin-js' );

        wp_enqueue_style( 'support-admin-icons',
            $this->url . '/assets/icons.css', null, $this->version );

        wp_enqueue_style( 'support-admin-css',
            $this->url . '/assets/admin/admin.css', null, $this->version );
    }

    public function swap_template( $template ) {
        if( is_page( get_option( Option::TEMPLATE_PAGE_ID ) ) ) {
            $template = $this->dir . '/template-parts/app.php';
        }

        return $template;
    }

    public function restore_template( $val ) {
        if( $val == 'on' ) {
            $this->setup_template();
        }

        return '';
    }

    public function add_caps( \WP_Role $role, $privileged = false ) {
        foreach( $this->caps( $privileged ) as $cap ) {
            $role->add_cap( $cap );
        }
    }

    public function remove_caps( \WP_Role $role, $privileged = false ) {
        foreach( $this->caps( $privileged ) as $cap ) {
            $role->remove_cap( $cap );
        }
    }

    private function caps( $privileged ) {
        $caps = array(
            'view_support_tickets',
            'create_support_tickets',
            'unfiltered_html'
        );

        if( $privileged ) {
            $caps[] = 'edit_others_tickets';
        }

        return $caps;
    }

    public function subscribed_hooks() {
        return array(
            'admin_enqueue_scripts' => array( 'admin_enqueue' ),
            'template_include' => array( 'swap_template' ),
            'pre_update_option_' . Option::RESTORE_TEMPLATE => array( 'restore_template' )
        );
    }

    public function components() {
        $components = array(
            TicketCptComponent::class
        );

        if( $this->edd_active || $this->woo_active ) {
            $components[] = ProductComponent::class;
        }

        if( get_option( Option::ALLOW_SIGNUPS ) == 'on' ) {
            $components[] = RegistrationComponent::class;
        }

        return $components;
    }

    public function roles() {
        return array(
            'support_admin' => __( 'Support Admin', PLUGIN_NAME ),
            'support_agent', __( 'Support Agent', PLUGIN_NAME ),
            'support_user', __( 'Support User', PLUGIN_NAME )
        );
    }

    private function setup_template() {
        $post_id = null;
        $post = get_post( get_option( Option::TEMPLATE_PAGE_ID ) ) ;

        if( empty( $post ) ) {
            $post_id = wp_insert_post(
                array(
                    'post_type' =>  'page',
                    'post_status' => 'publish',
                    'post_title' => __( 'Support', PLUGIN_NAME )
                )
            );
        } else if( $post->post_status == 'trash' ) {
            wp_untrash_post( $post->ID );

            $post_id = $post->ID;
        } else {
            $post_id = $post->ID;
        }

        if( !empty( $post_id ) ) {
            update_option( Option::TEMPLATE_PAGE_ID, $post_id );
        }
    }

    private function create_email_templates() {
        if( empty( get_post( get_option( Option::WELCOME_EMAIL_TEMPLATE ) ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => 'Welcome to Support',
                    'post_content'  => file_get_contents( $this->dir . '/emails/welcome.md' )
                )
            );

            if( !empty( $id ) ) {
                update_option( Option::WELCOME_EMAIL_TEMPLATE, $id );
            }
        }

        if( empty( get_post( get_option( Option::CLOSED_EMAIL_TEMPLATE ) ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => 'Your ticket has been closed',
                    'post_content'  => file_get_contents( $this->dir . '/emails/ticket_closed.md' )
                )
            );

            if( !empty( $id ) ) {
                update_option( Option::CLOSED_EMAIL_TEMPLATE, $id );
            }
        }
    }
}
