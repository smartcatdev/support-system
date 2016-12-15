<?php

use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_VERSION;

add_action( 'template_include', function ( $template ) {
    if( is_page( get_option( Option::TEMPLATE_PAGE_ID ) ) ) {
        $template = SUPPORT_PATH . '/templates/template.php';
    }

    return $template;
} );

add_action( 'plugins_loaded', function() {
    if( class_exists( 'WooCommerce' ) ) {
        define( 'SUPPORT_WOO_ACTIVE', 1 );
    }

    if( class_exists( 'Easy_Digital_Downloads' ) ) {
        define( 'SUPPORT_EDD_ACTIVE', 1 );
    }
} );


add_action( 'pre_update_option_' . Option::RESTORE_TEMPLATE_PAGE, function ( $value ) use ( $installer ) {
    if( $value == 'on' ) {
        $installer->register_template();
    }

    return '';
} );

if( get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ) ) {
    add_action( 'wp_ajax_nopriv_support_register_user', '\SmartcatSupport\register_user' );
}

// Temporarily add/remove roles until we get a settings page
add_action( 'admin_init', function() use ( $installer ) {
    if ( defined( 'SUPPORT_EDD_ACTIVE' ) ) {
        if ( get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ) ) {
            $installer->append_user_caps( 'subscriber' );
        } else {
            $installer->remove_appended_caps( 'subscriber' );
        }
    }

    if ( defined( 'SUPPORT_WOO_ACTIVE' ) ) {
        if ( get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ) ) {
            $installer->append_user_caps( 'customer' );
        } else {
            $installer->remove_appended_caps( 'customer' );
        }
    }
} );

register_activation_hook( $fs_context, array( $installer, 'activate' ) );
register_deactivation_hook( $fs_context, array( $installer, 'deactivate' ) );