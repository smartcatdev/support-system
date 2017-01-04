<?php

namespace SmartcatSupport;

use smartcat\core\AbstractPlugin;
use smartcat\core\HookSubscriber;
use smartcat\mail\Mailer;
use SmartcatSupport\admin\SettingsComponent;
use SmartcatSupport\component\ProductComponent;
use SmartcatSupport\component\RegistrationComponent;
use SmartcatSupport\component\TemplateComponent;
use SmartcatSupport\component\TicketCptComponent;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\UserUtils;

class Plugin extends AbstractPlugin implements HookSubscriber {

    public function start() {
        $this->add_api_subscriber( $this );
        $this->add_api_subscriber( include $this->dir . 'config/admin_settings.php' );

        $this->woo_active = class_exists( 'WooCommerce' );
        $this->edd_active = class_exists( 'Easy_Digital_Downloads' );

        // Notify subscribers if there is a version upgrade
        $version = get_option( Option::PLUGIN_VERSION, 0 );

        if( $this->version > $version ) {
            do_action( $this->id . '_upgrade', $version, $this->version );
            update_option( Option::PLUGIN_VERSION, $this->version );
        }

        Mailer::init( $this );

        include_once $this->dir . '/lib/tgm/TGM_Plugin_Activation.php';
    }

    public function activate() {
        do_action( $this->id . '_setup' );

        UserUtils::add_caps( add_role( 'support_admin', __( 'Support Admin', PLUGIN_ID ) ), true );
        UserUtils::add_caps( add_role( 'support_agent', __( 'Support Agent', PLUGIN_ID ) ), true );
        UserUtils::add_caps( add_role( 'support_user', __( 'Support User', PLUGIN_ID ) ) );
        UserUtils::add_caps( get_role( 'administrator' ), true );

        $this->create_email_templates();
    }

    public function deactivate() {
        UserUtils::remove_caps( get_role( 'administrator' ), true );

        remove_role( 'support_admin' );
        remove_role( 'support_agent' );
        remove_role( 'support_user' );

        do_action( $this->id . '_cleanup' );
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

    public function register_dependencies() {
        $plugins = array(
            array(
                'name'     => 'WP SMTP',
                'slug'     => 'wp-smtp',
                'required' => false
            )
        );

        $config = array(
            'id'           => 'smartcat_support_required_plugins',
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
                'notice_can_install_required' => _n_noop(
                    'Smartcat Support requires the following plugin: %1$s.',
                    'Smartcat Support requires the following plugins: %1$s.',
                    PLUGIN_ID
                ),
                'notice_can_install_recommended' => _n_noop(
                    'Smartcat Support recommends the following plugin: %1$s.',
                    'Smartcat Support recommends the following plugins: %1$s.',
                    PLUGIN_ID
                ),
            )
        );

        tgmpa( $plugins, $config );
    }

    public function subscribed_hooks() {
        return array(
            'admin_enqueue_scripts' => array( 'admin_enqueue' ),
            'tgmpa_register' => array( 'register_dependencies' )
        );
    }

    public function components() {
        $components = array(
            TicketCptComponent::class,
            TemplateComponent::class
        );

        if( $this->edd_active || $this->woo_active ) {
            $components[] = ProductComponent::class;
        }

        if( get_option( Option::ALLOW_SIGNUPS ) == 'on' ) {
            $components[] = RegistrationComponent::class;
        }

        return $components;
    }

    private function create_email_templates() {
        if( empty( get_post( get_option( Option::WELCOME_EMAIL_TEMPLATE ) ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => __( 'Welcome to Support', PLUGIN_ID ),
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
                    'post_title'    => __( 'Your ticket has been closed', PLUGIN_ID ),
                    'post_content'  => file_get_contents( $this->dir . '/emails/ticket_closed.md' )
                )
            );

            if( !empty( $id ) ) {
                update_option( Option::CLOSED_EMAIL_TEMPLATE, $id );
            }
        }
    }
}
