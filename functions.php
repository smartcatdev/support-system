<?php

namespace SmartcatSupport;

use SmartcatSupport\descriptor\Option;

function init( $fs_context ) {
    $app = array();

    // Configure the application's dependencies
    require_once 'app.php';

    add_shortcode( 'support-system', function() use ( $app ) {
        if( current_user_can( 'view_support_tickets' ) ) {
            echo $app['renderer']->render( 'dash' );
        }
    } );

    //<editor-fold desc="Enqueue Assets">
    add_action( 'wp_enqueue_scripts', function() use ( $app ) {
        wp_enqueue_script( 'datatables',
            $app['plugin_url'] . 'assets/lib/datatables/datatables.min.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_style( 'datatables',
            $app['plugin_url'] . 'assets/lib/datatables/datatables.min.css', [], PLUGIN_VERSION );

        wp_enqueue_style( 'jquery-modal',
            $app['plugin_url'] . 'assets/lib/modal/jquery.modal.min.css', [], PLUGIN_VERSION );

        wp_enqueue_script( 'jquery-modal',
            $app['plugin_url'] . 'assets/lib/modal/jquery.modal.min.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_script( 'tabular',
            $app['plugin_url'] . 'assets/lib/tabular.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_script( 'tinymce_js',
            includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', [ 'jquery' ], false, true );

        wp_register_script( 'support_system_lib',
            $app['plugin_url'] . 'assets/js/app.js', [ 'jquery', 'jquery-ui-tabs' ], PLUGIN_VERSION );

        wp_localize_script( 'support_system_lib', 'SupportSystem', [ 'ajaxURL' => admin_url( 'admin-ajax.php' ) ] );
        wp_enqueue_script( 'support_system_lib' );

        wp_enqueue_script( 'support_system_script',
            $app['plugin_url'] . 'assets/js/script.js', [ 'jquery', 'jquery-ui-tabs', 'jquery-ui-core', 'support_system_lib' ], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_style',
            $app['plugin_url'] . 'assets/css/style.css', [], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_icons',
            $app['plugin_url'] . 'assets/icons.css', [], PLUGIN_VERSION );
    } );
    //</editor-fold>

    add_action( 'plugins_loaded', function() {
        if( class_exists( 'WooCommerce' ) ) {
            update_option( Option::WOOCOMMERCE_ACTIVE, true );
        }

        if( class_exists( 'Easy_Digital_Downloads' ) ){
            update_option( Option::EDD_ACTIVE, true );
        }
    } );

    register_activation_hook( $fs_context, array( $app['installer'], 'activate' ) );
    register_deactivation_hook( $fs_context, array( $app['installer'], 'deactivate' ) );
}

function convert_html_chars( $text ) {
    $matches = array();

    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );

    foreach( $matches[1] as $match ) {
        $text = str_replace( $match, htmlspecialchars( $match ), $text );
    }

    return $text;
}

function get_agents() {
    $agents = array();

    $users = get_users( array( 'role' => array( 'support_agent' ) ) );

    if( $users != null ) {
        foreach( $users as $user ) {
            $agents[ $user->ID ] = $user->display_name;
        }
    }

    return $agents;
}

function get_products() {
    $results = false;

    if( get_option( Option::EDD_ACTIVE, Option\Defaults::EDD_ACTIVE ) ) {
        $args = array(
            'post_type' => 'download',
            'post_status' => 'publish',
        );

        $query = new \WP_Query( $args );

        while( $query->have_posts() ) {
            $results[ $query->post->ID ] = $query->post->post_title;

            $query->next_post();
        }
    }

    if( get_option( Option::WOOCOMMERCE_ACTIVE, Option\Defaults::WOOCOMMERCE_ACTIVE ) ) {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
        );

        $query = new \WP_Query( $args );

        while( $query->have_posts() ) {
            $results[ $query->post->ID ] = $query->post->post_title;

            $query->next_post();
        }
    }

    return $results;
}
