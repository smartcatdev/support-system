<?php
/**
 * Functions for managing scripts on the application's front-end.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Init scrips on load
add_action( 'ucare_loaded', 'ucare\init_scripts' );

// Fire our enqueue hook
add_action( 'wp', 'ucare\enqueue_system_scripts' );

// Register core scripts
add_action( 'ucare_enqueue_scripts', 'ucare\register_default_scripts' );

// Load default scripts
add_action( 'ucare_enqueue_scripts', 'ucare\enqueue_default_scripts' );

// Print header scripts
add_action( 'ucare_head', 'ucare\print_header_scripts' );

// Print footer scripts
add_action( 'ucare_footer', 'ucare\print_footer_scripts' );


/**
 * Initialize the script and style service.
 *
 * @param uCare $ucare The plugin instance.
 *
 * @action ucare_loaded
 *
 * @since 1.4.2
 * @return void
 */
function init_scripts( $ucare ) {
    $ucare->set( 'scripts', new \WP_Scripts() );
    $ucare->set( 'styles',  new \WP_Styles()  );
}


/**
 * Fires the ucare_enqueue_scripts action at the earliest moment we know that we are on the support page.
 *
 * @action wp
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_system_scripts() {

    if ( is_a_support_page() ) {
        do_action( 'ucare_enqueue_scripts' );
    }

}


/**
 * Register core script dependencies.
 *
 * @action ucare_enqueue_scripts
 *
 * @since 1.6.0
 * @return void
 */
function register_default_scripts() {

    $i10n = array(
        'vars' => array(
            'support_url' => support_page_url()
        ),
        'api' => array(
            'nonce'  => wp_create_nonce( 'wp_rest' ),
            'root'   => rest_url()
        ),
        'i10n' => array(

        ),
        'settings' => array(
            'max_file_size' => get_option( Options::MAX_ATTACHMENT_SIZE )
        )
    );

    ucare_register_script( 'ucare', resolve_url( 'assets/js/ucare.js' ), array( 'jquery' ), PLUGIN_VERSION );
    ucare_localize_script( 'ucare', 'ucare_i10n', $i10n );


    // Register jQuery plugins
    ucare_register_script( 'jquery-serializejson', resolve_url( 'assets/js/jquery-serializejson.js' ), array( 'jquery' ), PLUGIN_VERSION );

}



/**
 * Enqueue all of the scripts needed for the system's front-end.
 *
 * @action ucare_enqueue_scripts
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_default_scripts() {

    // Scripts
    ucare_enqueue_script( 'ucare' );
    ucare_enqueue_script( 'jquery' );
    ucare_enqueue_script( 'wp-util' );

    ucare_enqueue_script( 'bootstrap', resolve_url( 'assets/lib/bootstrap/js/bootstrap.min.js' ), null, PLUGIN_VERSION, true );
    ucare_enqueue_script( 'dropzone',  resolve_url( 'assets/lib/dropzone/js/dropzone.min.js'   ), null, PLUGIN_VERSION, true );

    ucare_enqueue_script( 'script', resolve_url( 'assets/js/script.js' ), null, PLUGIN_VERSION, true );


    // Only load these scripts in the app
    if ( is_support_page() ) {
        ucare_enqueue_script( 'scrolling-tabs',    resolve_url( 'assets/lib/scrollingTabs/scrollingTabs.min.js'  ), null, PLUGIN_VERSION, true );
        ucare_enqueue_script( 'light-gallery',     resolve_url( 'assets/lib/lightGallery/js/lightgallery.min.js' ), null, PLUGIN_VERSION, true );
        ucare_enqueue_script( 'moment',            resolve_url( 'assets/lib/moment/moment.min.js'                ), null, PLUGIN_VERSION, true );
        ucare_enqueue_script( 'lg-zoom',           resolve_url( 'assets/lib/lightGallery/plugins/lg-zoom.min.js' ), null, PLUGIN_VERSION, true );
        ucare_enqueue_script( 'textarea-autosize', resolve_url( 'assets/lib/textarea-autosize.min.js'            ), null, PLUGIN_VERSION, true );

        enqueue_app();

        ucare_enqueue_script( 'ucare-plugins',  resolve_url( 'assets/js/plugins.js'  ), null, PLUGIN_VERSION, true );
        ucare_enqueue_script( 'ucare-tickets',  resolve_url( 'assets/js/ticket.js'   ), null, PLUGIN_VERSION, true );
        ucare_enqueue_script( 'ucare-comments', resolve_url( 'assets/js/comment.js'  ), null, PLUGIN_VERSION, true );
    }

    // Load create ticket page scripts
    else if ( is_create_ticket_page() ) {
        ucare_enqueue_script( 'ucare-create-ticket', resolve_url( 'assets/js/create-ticket.js' ), array( 'ucare', 'jquery-serializejson' ), PLUGIN_VERSION, true );

    // Load edit profile page scripts
    } else if ( is_edit_profile_page() ) {
        ucare_enqueue_script( 'ucare-edit-profile', resolve_url( 'assets/js/edit-profile.js' ), array( 'ucare', 'jquery-serializejson' ), PLUGIN_VERSION, true );

    // Load login page scripts
    } else if ( is_login_page() ) {
        ucare_enqueue_script( 'ucare-login', resolve_url( 'assets/js/login.js' ), array( 'ucare', 'jquery-serializejson' ), PLUGIN_VERSION, true );
    }

}


/**
 * Localizes and enqueues the core app script.
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_app() {

    $i10n = array(
        'ajax_nonce'          => wp_create_nonce( 'support_ajax' ),
        'ajax_url'            => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
        'refresh_interval'    => abs( get_option( Options::REFRESH_INTERVAL, Defaults::REFRESH_INTERVAL ) ),
        'max_attachment_size' => get_option( Options::MAX_ATTACHMENT_SIZE, Defaults::MAX_ATTACHMENT_SIZE ),
        'strings' => array(
            'loading_tickets'   => __( 'Loading Tickets...', 'ucare' ),
            'loading_generic'   => __( 'Loading...', 'ucare' ),
            'delete_comment'    => __( 'Delete Comment', 'ucare' ),
            'delete_attachment' => __( 'Delete Attachment', 'ucare' ),
            'close_ticket'      => __( 'Close Ticket', 'ucare' ),
            'warning_permanent' => __( 'Are you sure you want to do this? This operation cannot be undone!', 'ucare' ),
            'yes' => __( 'Yes', 'ucare' ),
            'no'  => __( 'No', 'ucare' ),
        )
    );

    ucare_register_script( 'ucare-app', resolve_url( 'assets/js/app.js' ), null, PLUGIN_VERSION, true );
    ucare_localize_script( 'ucare-app', 'Globals', $i10n );

    ucare_enqueue_script( 'ucare-app' );

}


/**
 * Print enqueued header scripts.
 *
 * @action ucare_head
 *
 * @since 1.4.2
 * @return bool|array
 */
function print_header_scripts() {

    $scripts = scripts();

    if ( $scripts && !did_action( 'ucare_print_header_scripts' ) ) {
        do_action( 'ucare_print_header_scripts' );

        $scripts->do_head_items();
        $scripts->reset();

        return $scripts->done;
    }

    return false;

}


/**
 * Print enqueued footer scripts.
 *
 * @since 1.4.2
 * @return bool|array
 */
function print_footer_scripts() {

    $scripts = scripts();

    if ( $scripts && !did_action( 'ucare_print_footer_scripts' ) ) {
        do_action( 'ucare_print_footer_scripts' );

        print_underscore_templates();
        print_footer_scripts();

        $scripts->do_footer_items();
        $scripts->reset();

        return $scripts->done;
    }

    return false;

}


/**
 * Get scripts object.
 *
 * @since 1.4.2
 * @return false|\WP_Scripts
 */
function scripts() {
    return ucare()->get( 'scripts' );
}

