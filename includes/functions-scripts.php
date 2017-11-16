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

// Load default scripts
add_action( 'ucare_enqueue_scripts', 'ucare\enqueue_default_scripts' );


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
function init_scripts( uCare $ucare ) {

    $ucare->set( 'scripts', new \WP_Scripts() );
    $ucare->set( 'styles', new \WP_Styles() );

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

    if ( get_option( Options::TEMPLATE_PAGE_ID ) == get_the_ID() ) {
        do_action( 'ucare_enqueue_scripts' );
    }

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

    // Styles
    enqueue_style( 'dropzone',       resolve_url( 'assets/lib/dropzone/css/dropzone.min.css'         ), null, PLUGIN_VERSION );
    enqueue_style( 'bootstrap',      resolve_url( 'assets/lib/bootstrap/css/bootstrap.min.css'       ), null, PLUGIN_VERSION );
    enqueue_style( 'scrolling-tabs', resolve_url( 'assets/lib/scrollingTabs/scrollingTabs.min.css'   ), null, PLUGIN_VERSION );
    enqueue_style( 'light-gallery',  resolve_url( 'assets/lib/lightGallery/css/lightgallery.min.css' ), null, PLUGIN_VERSION );

    enqueue_style( 'ucare-style',    resolve_url( 'assets/css/style.css' ), null, PLUGIN_VERSION );

    enqueue_fonts();


    // Scripts
    enqueue_script( 'jquery' );
    enqueue_script( 'wp-util' );
    enqueue_script( 'moment',            resolve_url( 'assets/lib/moment/moment.min.js'                ), null, PLUGIN_VERSION, true );
    enqueue_script( 'bootstrap',         resolve_url( 'assets/lib/bootstrap/js/bootstrap.min.js'       ), null, PLUGIN_VERSION, true );
    enqueue_script( 'dropzone',          resolve_url( 'assets/lib/dropzone/js/dropzone.min.js'         ), null, PLUGIN_VERSION, true );
    enqueue_script( 'scrolling-tabs',    resolve_url( 'assets/lib/scrollingTabs/scrollingTabs.min.js'  ), null, PLUGIN_VERSION, true );
    enqueue_script( 'light-gallery',     resolve_url( 'assets/lib/lightGallery/js/lightgallery.min.js' ), null, PLUGIN_VERSION, true );
    enqueue_script( 'lg-zoom',           resolve_url( 'assets/lib/lightGallery/plugins/lg-zoom.min.js' ), null, PLUGIN_VERSION, true );
    enqueue_script( 'textarea-autosize', resolve_url( 'assets/lib/textarea-autosize.min.js'            ), null, PLUGIN_VERSION, true );

    enqueue_app();

    enqueue_script( 'ucare-plugins',  resolve_url( 'assets/js/plugins.js'  ), null, PLUGIN_VERSION, true );
    enqueue_script( 'ucare-settings', resolve_url( 'assets/js/settings.js' ), null, PLUGIN_VERSION, true );
    enqueue_script( 'ucare-settings', resolve_url( 'assets/js/settings.js' ), null, PLUGIN_VERSION, true );
    enqueue_script( 'ucare-tickets',  resolve_url( 'assets/js/ticket.js'   ), null, PLUGIN_VERSION, true );
    enqueue_script( 'ucare-comments', resolve_url( 'assets/js/comment.js'  ), null, PLUGIN_VERSION, true );

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

    register_script( 'ucare-app', resolve_url( 'assets/js/app.js' ), null, PLUGIN_VERSION, true );
    localize_script( 'ucare-app', 'Globals', $i10n );

    enqueue_script( 'ucare-app' );

}


/**
 * Enqueue a script in the front-end application. Can be called at any point before output begins, However enqueuing is
 * not available until after ucare_loaded has fired. It is recommended to enqueue scripts in the ucare_enqueue_scripts
 * hook.
 *
 * @see wp_enqueue_script
 *
 * @param string $handle    The handle of the script to enqueue.
 * @param string $src       The URL of the script.
 * @param array  $deps      Any dependencies the script requires.
 * @param bool   $ver       A version for the script.
 * @param bool   $in_footer Whether the script should be printed in the footer.
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {

    $scripts = scripts();

    if ( $scripts ) {

        if ( $src || $in_footer ) {

            $_handle = explode( '?', $handle );
            $scripts->add( $_handle[0], $src, $deps, $ver );

            if ( $in_footer ) {
                $scripts->add_data( $_handle[0], 'group', 1 );
            }

        }

        $scripts->enqueue( $handle );

    }

}


function enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {

    $styles = styles();

    if ( $styles ) {

        if ( $src ) {
            $_handle = explode('?', $handle);
            $styles->add( $_handle[0], $src, $deps, $ver, $media );
        }

        return $styles->enqueue( $handle );

    }

    return false;

}


function register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {

    $scripts = scripts();

    if ( $scripts ) {

        $registered = $scripts->add( $handle, $src, $deps, $ver );

        if ( $in_footer ) {
            $scripts->add_data( $handle, 'group', 1 );
        }

        return $registered;

    }

    return false;

}


function localize_script( $handle, $object_name, $i10n ) {

    $scripts = scripts();

    if ( $scripts ) {
        $scripts->localize( $handle, $object_name, $i10n );
    }

    return false;

}


function register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {

    $styles = styles();

    if ( $styles ) {
        return $styles->add( $handle, $src, $deps, $ver, $media );
    }

    return true;

}


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


function print_footer_scripts() {

    $scripts = scripts();

    if ( $scripts && !did_action( 'ucare_print_footer_scripts' ) ) {

        do_action( 'ucare_print_footer_scripts' );

        $scripts->do_footer_items();
        $scripts->reset();

        return $scripts->done;

    }

    return false;

}


function print_styles() {

    $styles = styles();

    if ( $styles ) {

        $styles->do_items();
        $styles->reset();

        return $styles->done;

    }

    return false;

}


function scripts() {
    return ucare()->get( 'scripts' );
}


function styles() {
    return ucare()->get( 'styles' );
}
