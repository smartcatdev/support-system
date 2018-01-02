<?php
/**
 * Functions for managing styles on the application's front-end.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Load default styles
add_action( 'ucare_enqueue_scripts', 'ucare\enqueue_default_styles' );


/**
 * Load default system styles.
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_default_styles() {

    enqueue_fonts();

    ucare_enqueue_style( 'bootstrap',   resolve_url( 'assets/lib/bootstrap/css/bootstrap.min.css' ), null, PLUGIN_VERSION );
    ucare_enqueue_style( 'dropzone',    resolve_url( 'assets/lib/dropzone/css/dropzone.min.css'   ), null, PLUGIN_VERSION );

    ucare_enqueue_style( 'ucare-dropzone', resolve_url( 'assets/css/dropzone.css' ), null, PLUGIN_VERSION );
    ucare_enqueue_style( 'ucare-style',    resolve_url( 'assets/css/style.css'    ), null, PLUGIN_VERSION );

    // Only load these styles in the app
    if ( is_support_page() ) {
        ucare_enqueue_style( 'scrolling-tabs', resolve_url( 'assets/lib/scrollingTabs/scrollingTabs.min.css'   ), null, PLUGIN_VERSION );
        ucare_enqueue_style( 'light-gallery',  resolve_url( 'assets/lib/lightGallery/css/lightgallery.min.css' ), null, PLUGIN_VERSION );
    }

}


/**
 * Print enqueued styles.
 *
 * @since 1.4.2
 * @return array|bool
 */
function print_styles() {

    $styles = styles();

    if ( $styles && !did_action( 'ucare_print_styles' ) ) {
        do_action( 'ucare_print_styles' );

        $styles->do_items();
        $styles->reset();

        return $styles->done;
    }

    return false;
}


/**
 * Get the styles object.
 *
 * @since 1.4.2
 * @return false|\WP_Scripts
 */
function styles() {
    return ucare()->get( 'styles' );
}
