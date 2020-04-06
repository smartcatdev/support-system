<?php
/**
 * Functions for managing the WordPress admin menus
 */
namespace ucare;


// Add menu pages
add_action( 'admin_menu', fqn( 'add_menu_pages' ), 1000 );

// Do first run page
add_action( 'admin_init', fqn( 'admin_first_run_tutorial_page' ) );

// Set the submenu file
add_filter( 'submenu_file', 'ucare\set_submenu_file' );


/**
 * Add admin menu pages.
 *
 * @action admin_menu
 *
 * @since 1.6.0
 * @return void
 */
function add_menu_pages() {
    add_submenu_page( 'ucare_support', '', __( 'Email Templates', 'ucare' ), 'manage_options', 'edit.php?post_type=email_template' );
    ucare_add_admin_page( TutorialPage::class );
    ucare_add_admin_page( AddonsPage::class );
}


/**
 * Redirect the user to the tutorials page when the plugin installs/updates.
 *
 * @action admin_init
 *
 * @since 1.6.0
 * @return void
 */
function admin_first_run_tutorial_page() {
    if ( (bool) get_option( Options::FIRST_160_RUN ) ) {
        return;
    }

    update_option( Options::FIRST_160_RUN, true );
    wp_redirect( '?page=ucare-tutorial' );
}


/**
 * Manually set the submenu page open.
 *
 * @global $parent_file
 * @global $current_screen
 *
 * @param $submenu_file
 *
 * @since 1.0.0
 * @return string
 */
function set_submenu_file( $submenu_file ) {
    global $parent_file, $current_screen;

    if ( $current_screen->taxonomy === 'ticket_category' ) {
        $parent_file = 'ucare_support';
        $submenu_file = 'edit-tags.php?post_type=support_ticket&taxonomy=ticket_category';
    } else if ( $current_screen->base === 'post' ) {
        $types = array(
            'support_ticket',
            'email_template'
        );
        if ( in_array( $current_screen->post_type, $types ) ) {
            $parent_file = 'ucare_support';
        }
    }
    return $submenu_file;
}





/***********************************************************************************************************************
 *
 * TODO Needs desperate refactoring
 */
add_action( 'admin_enqueue_scripts', 'ucare\enqueue_admin_scripts' );

// Include admin sidebar on options page
add_action( 'uc-settings_menu_page', 'ucare\admin_page_sidebar' );


add_action( 'ucare_admin_header', 'ucare\get_admin_header' );

add_action( 'uc-settings_admin_page_header', 'ucare\get_admin_header' );




function enqueue_admin_scripts( $hook ) {

    wp_enqueue_script( 'ucare-admin-global',
        resolve_url( '/assets/admin/global.js' ), array( 'jquery', 'wp-color-picker' ), PLUGIN_VERSION );

    wp_enqueue_style( 'ucare-admin-global',
        resolve_url( '/assets/admin/global.css' ), null, PLUGIN_VERSION );
    
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    
    
    // Load assets only on plugin admin pages
    if ( strpos( $hook, 'ucare' ) !== false || get_post_type() == 'support_ticket' ) {

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_script( 'wp_media_uploader',
            resolve_url( 'assets/lib/wp_media_uploader.js' ), array( 'jquery' ), PLUGIN_VERSION );

        wp_enqueue_style( 'support-admin-icons',
            resolve_url( '/assets/icons/style.css' ), null, PLUGIN_VERSION );

        wp_register_script( 'support-admin-js',
            resolve_url( 'assets/admin/admin.js' ), array( 'jquery' ), PLUGIN_VERSION );

        wp_localize_script( 'support-admin-js',
            'SupportSystem', array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce( 'support_ajax' ),
                'select_image' => __( 'Select image', 'ucare' )
            )
        );

        wp_enqueue_script( 'support-admin-js' );

        wp_enqueue_style( 'support-admin-css',
            resolve_url( '/assets/admin/admin.css' ), null, PLUGIN_VERSION );


        //<editor-fold desc="Libraries">
        wp_enqueue_media();

        wp_enqueue_script( 'moment',
            resolve_url( '/assets/lib/moment/moment.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'selectize-js',
            resolve_url( '/assets/lib/selectize/js/selectize.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_style( 'selectize-css',
            resolve_url( '/assets/lib/selectize/css/selectize.css' ), null, PLUGIN_VERSION );
        
        wp_enqueue_script( 'flot',
            resolve_url( '/assets/lib/flot/jquery.flot.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'flot-time',
            resolve_url( '/assets/lib/flot/jquery.flot.time.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'flot-resize',
            resolve_url( '/assets/lib/flot/jquery.flot.resize.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'moment',
            resolve_url( '/assets/lib/moment/moment.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'ucare-reports-js',
            resolve_url( '/assets/admin/reports.js' ), null, PLUGIN_VERSION );

        wp_enqueue_style( 'ucare-reports-css',
            resolve_url( '/assets/admin/reports.css' ), null, PLUGIN_VERSION );
        //</editor-fold>

    }

}


function admin_page_sidebar() {
    include_once plugin_dir() . '/templates/admin-sidebar.php';
}


function get_admin_header( $echo = true ) {

    $header = buffer_template( 'admin-header' );


    if ( $echo !== false ) {
        echo $header;
    }

    return $header;

}
