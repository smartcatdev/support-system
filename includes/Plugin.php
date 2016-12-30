<?php

namespace SmartcatSupport;

use smartcat\core\AbstractPlugin;
use smartcat\core\HookSubscriber;
use SmartcatSupport\descriptor\Option;

class Plugin extends AbstractPlugin implements HookSubscriber {

    public function start() {
        $this->add_api_subscriber( $this );

        if( class_exists( 'WooCommerce' ) ) {
            $this->edd_enabled = true;
        }

        if( class_exists( 'Easy_Digital_Downloads' ) ) {
            $this->woo_enabled = true;
        }

        // Notify subscribers if there is a version upgrade
        $version = get_option( Option::PLUGIN_VERSION, 0 );

        if( $this->version > $version ) {
            do_action( $this->name . '_upgrade', $version, $this->version );
            update_option( Option::PLUGIN_VERSION, $this->version );
        }
    }

    public function activate() {

    }

    public function deactivate() {

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

    public function subscribed_hooks() {
        return array(
            'admin_enqueue_scripts' => array( 'admin_enqueue' )
        );
    }

    public function components() {
        return array();
    }
}
