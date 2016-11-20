<?php

namespace SmartcatSupport;

use SmartcatSupport\admin\SupportMetaBox;
use SmartcatSupport\ajax\Comment;
use SmartcatSupport\ajax\Ticket;
use SmartcatSupport\ajax\TicketTable;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\field\Hidden;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\util\Installer;
use SmartcatSupport\util\TemplateRender;

/**
 * Composition Root for the plugin.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 */
function init() {

    // Configure the application
    $plugin_dir = plugin_dir_path( __FILE__ );
    $plugin_url = plugin_dir_url( __FILE__ );

    // Form Builder
    $form_builder = new FormBuilder( 'support_form' );

    // Configure table Handler
    $table_handler = new TicketTable( $form_builder );

    // Configure ticket Handler
    $ticket_handler = new Ticket( $form_builder );

    // Configure comment handler
    $comment_handler = new Comment( $form_builder );

    // Configure the metabox
    $support_metabox = new SupportMetaBox( $form_builder );

    // Configure installer
    $installer = new Installer();

    add_shortcode( 'support-system', function() {
        if( is_user_logged_in() && current_user_can( 'view_support_tickets' ) ) {
            echo render_template( 'dash' );
        } else {
            wp_login_form();
        }
    } );

    //<editor-fold desc="Enqueue Assets">
    add_action( 'wp_enqueue_scripts', function() use ( $plugin_url ) {
        wp_enqueue_script( 'datatables',
            $plugin_url . 'assets/lib/datatables/datatables.min.js', array( 'jquery' ), PLUGIN_VERSION );

        wp_enqueue_style( 'datatables',
            $plugin_url . 'assets/lib/datatables/datatables.min.css', array(), PLUGIN_VERSION );

        wp_enqueue_style( 'jquery-modal',
            $plugin_url . 'assets/lib/modal/jquery.modal.min.css', array(), PLUGIN_VERSION );

        wp_enqueue_script( 'jquery-modal',
            $plugin_url . 'assets/lib/modal/jquery.modal.min.js', array( 'jquery' ), PLUGIN_VERSION );

        wp_enqueue_script( 'tabular',
            $plugin_url . 'assets/lib/tabular.js', array('jquery' ), PLUGIN_VERSION );

        wp_enqueue_script( 'tinymce_js',
            includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', array( 'jquery' ), false, true );

        wp_register_script( 'support_system_lib',
            $plugin_url . 'assets/js/app.js', array( 'jquery', 'jquery-ui-tabs' ), PLUGIN_VERSION );

        wp_localize_script( 'support_system_lib', 'SupportSystem', array( 'ajaxURL' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script( 'support_system_lib' );

        wp_enqueue_script( 'support_system_script',
            $plugin_url . 'assets/js/script.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-core', 'support_system_lib' ), PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_style',
            $plugin_url . 'assets/css/style.css', array(), PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_icons',
            $plugin_url . 'assets/icons.css', array(), PLUGIN_VERSION );
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

/**
 * Decode HTML chars between <code></code> tags.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 * @param $text
 * @return String
 */
function convert_html_chars( $text ) {
    $matches = array();

    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );

    foreach( $matches[1] as $match ) {
        $text = str_replace( $match, htmlspecialchars( $match ), $text );
    }

    return $text;
}

/**
 * Get a list of all users with the Support Agent Role.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @return array The list of agents
 * @since 1.0.0
 */
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

/**
 * Get a list of Products and/or Downloads if EDD or WooCommerce is active.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @return bool|array False if neither is active, else an array of post titles and IDs.
 * @since 1.0.0
 */
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

/**
 * Render the template and capture its output.
 *
 * @param string $template The template to render.
 * @param array $data (Default empty) Any data required to be output in the template.
 * @return string The rendered HTML.
 * @since 1.0.0
 * @author Eric Green <eric@smartcat.ca>
 */
function render_template( $template, array $data = array() ) {
    if( is_array( $data ) ) {
        extract( $data );
    }

    ob_start();

    include ( plugin_dir_path( __FILE__ ) . 'templates/' . $template . '.php' );

    return ob_get_clean();
}
