<?php

namespace SmartcatSupport;

use SmartcatSupport\admin\SupportMetaBox;
use SmartcatSupport\ajax\Comment;
use SmartcatSupport\ajax\Ticket;
use SmartcatSupport\ajax\TicketTable;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\util\Installer;
use SmartcatSupport\util\TemplateRender;

function init() {

    // Configure the application
    $plugin_dir = plugin_dir_path( __FILE__ );
    $plugin_url = plugin_dir_url( __FILE__ );


    // Default template rendering
    $renderer = new TemplateRender( $plugin_dir . '/templates' );

    // Form Builder
    $form_builder = new FormBuilder( 'support_form' );

    // Configure table Handler
    $table_handler = new TicketTable( $renderer, $form_builder );

    // Configure ticket Handler
    $ticket_handler = new Ticket( $renderer, $form_builder );

    // Configure comment handler
    $comment_handler = new Comment( $renderer, $form_builder );

    // Configure the metabox
    $support_metabox = new SupportMetaBox( $renderer, $form_builder );

    // Configure installer
    $installer = new Installer();

    add_shortcode( 'support-system', function() use ( $renderer ) {
        if( current_user_can( 'view_support_tickets' ) ) {
            echo $renderer->render( 'dash' );
        }
    } );

    //<editor-fold desc="Enqueue Assets">
    add_action( 'wp_enqueue_scripts', function() use ( $plugin_url ) {
        wp_enqueue_script( 'datatables',
            $plugin_url . 'assets/lib/datatables/datatables.min.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_style( 'datatables',
            $plugin_url . 'assets/lib/datatables/datatables.min.css', [], PLUGIN_VERSION );

        wp_enqueue_style( 'jquery-modal',
            $plugin_url . 'assets/lib/modal/jquery.modal.min.css', [], PLUGIN_VERSION );

        wp_enqueue_script( 'jquery-modal',
            $plugin_url . 'assets/lib/modal/jquery.modal.min.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_script( 'tabular',
            $plugin_url . 'assets/lib/tabular.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_script( 'tinymce_js',
            includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', [ 'jquery' ], false, true );

        wp_register_script( 'support_system_lib',
            $plugin_url . 'assets/js/app.js', [ 'jquery', 'jquery-ui-tabs' ], PLUGIN_VERSION );

        wp_localize_script( 'support_system_lib', 'SupportSystem', [ 'ajaxURL' => admin_url( 'admin-ajax.php' ) ] );
        wp_enqueue_script( 'support_system_lib' );

        wp_enqueue_script( 'support_system_script',
            $plugin_url . 'assets/js/script.js', [ 'jquery', 'jquery-ui-tabs', 'jquery-ui-core', 'support_system_lib' ], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_style',
            $plugin_url . 'assets/css/style.css', [], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_icons',
            $plugin_url . 'assets/icons.css', [], PLUGIN_VERSION );
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

    register_activation_hook( __FILE__, array( $installer, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $installer, 'deactivate' ) );
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
