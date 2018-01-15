<?php

namespace ucare;


add_action( 'admin_enqueue_scripts', 'ucare\enqueue_admin_scripts' );

// Include admin sidebar on options page
add_action( 'uc-settings_menu_page', 'ucare\admin_page_sidebar' );

add_action( 'admin_menu', 'ucare\add_admin_menu_pages' );

add_action( 'admin_init', 'ucare\admin_first_run_tutorial_page' );

add_action( 'ucare_admin_header', 'ucare\get_admin_header' );

add_action( 'uc-settings_admin_page_header', 'ucare\get_admin_header' );

add_filter( 'submenu_file', 'ucare\set_submenu_file' );


function enqueue_admin_scripts( $hook ) {

    wp_enqueue_script( 'ucare-admin-global',
        resolve_url( '/assets/admin/global.js' ), array( 'jquery', 'wp-color-picker' ), PLUGIN_VERSION );

    wp_enqueue_style( 'ucare-admin-global',
        resolve_url( '/assets/admin/global.css' ), null, PLUGIN_VERSION );
    
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    
    
    // Load assets only on plugin admin pages
    if ( strpos( $hook, 'ucare' ) !== false || get_post_type() == 'support_ticket' ) {

        wp_enqueue_script( 'wp_media_uploader',
            resolve_url( 'assets/lib/wp_media_uploader.js' ), array( 'jquery' ), PLUGIN_VERSION );

        wp_enqueue_style( 'support-admin-icons',
            resolve_url( '/assets/icons/style.css' ), null, PLUGIN_VERSION );

        wp_register_script( 'support-admin-js',
            resolve_url( 'assets/admin/admin.js' ), array( 'jquery' ), PLUGIN_VERSION );

        wp_localize_script( 'support-admin-js',
            'SupportSystem', array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce( 'support_ajax' )
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


function add_admin_menu_pages() {

    add_submenu_page( null, __( 'uCare - Introduction', 'ucare' ), __( 'uCare - Introduction', 'ucare' ), 'manage_options', 'uc-tutorial', function() { include_once plugin_dir() . 'templates/admin-tutorial.php'; } );

}


function admin_first_run_tutorial_page() {

    if( ! get_option( Options::FIRST_RUN ) ) {

        update_option( Options::FIRST_RUN, true );
        wp_safe_redirect( admin_url( 'admin.php?page=uc-tutorial' ) );

    }

    if( ! get_option( Options::FIRST_140_RUN ) ) {

        update_option( Options::FIRST_140_RUN, true );
        wp_safe_redirect( admin_url( 'admin.php?page=uc-tutorial' ) );

    }

}


function get_admin_header( $echo = true ) {

    $header = buffer_template( 'admin-header' );


    if ( $echo !== false ) {
        echo $header;
    }

    return $header;

}


function set_submenu_file( $submenu_file ) {

    global $parent_file, $current_screen;

    if ( $current_screen->taxonomy === 'ticket_category' ) {
        $parent_file = 'ucare_support';
        $submenu_file = 'edit-tags.php?post_type=support_ticket&taxonomy=ticket_category';
    } else if ( $current_screen->base === 'post' && $current_screen->post_type == 'support_ticket' ) {
        $parent_file = 'ucare_support';
    }

    return $submenu_file;

}

