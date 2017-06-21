<?php

namespace ucare;

function enqueue_admin_scripts( $hook ) {

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );

    wp_enqueue_script( 'wp_media_uploader',
        plugin_url( 'assets/lib/wp_media_uploader.js' ), array( 'jquery' ), PLUGIN_VERSION );

    wp_enqueue_style( 'support-admin-icons',
        plugin_url( '/assets/icons/style.css' ), null, PLUGIN_VERSION );

    wp_register_script('support-admin-js',
        plugin_url( 'assets/admin/admin.js' ), array( 'jquery' ), PLUGIN_VERSION );

    wp_localize_script( 'support-admin-js',
        'SupportSystem', array(
            'ajax_url'   => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'support_ajax' )
        )
    );

    wp_enqueue_media();
    wp_enqueue_script( 'support-admin-js' );

    wp_enqueue_style( 'support-admin-css',
        plugin_url( '/assets/admin/admin.css' ), null, PLUGIN_VERSION );

    if( strpos( $hook, 'ucare' ) !== false ) {

        wp_enqueue_script( 'moment',
            plugin_url( '/assets/lib/moment/moment.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'flot',
            plugin_url( '/assets/lib/flot/jquery.flot.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'flot-time',
            plugin_url( '/assets/lib/flot/jquery.flot.time.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'flot-resize',
            plugin_url( '/assets/lib/flot/jquery.flot.resize.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'moment',
            plugin_url( '/assets/lib/moment/moment.min.js' ), null, PLUGIN_VERSION );

        wp_enqueue_script( 'ucare-reports-js',
            plugin_url( '/assets/admin/reports.js' ), null, PLUGIN_VERSION );

        wp_enqueue_style( 'ucare-reports-css',
            plugin_url( '/assets/admin/reports.css' ), null, PLUGIN_VERSION );

    }

}

add_action( 'admin_enqueue_scripts', 'ucare\enqueue_admin_scripts' );


function admin_page_header() {
    include_once plugin_dir() . '/templates/admin-header.php';
}

// Include admin header
add_action( 'support_options_admin_page_header', 'ucare\admin_page_header' );


function admin_page_sidebar() {
    include_once plugin_dir() . '/templates/admin-sidebar.php';
}

// Include admin sidebar on options page
add_action( 'uc-settings_menu_page', 'ucare\admin_page_sidebar' );
