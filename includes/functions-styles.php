<?php
/**
 * Functions for managing styles on the application's front-end.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

// Init styles on load
add_action( 'ucare_loaded', 'ucare\init_styles' );

// Load default styles
add_action( 'ucare_enqueue_scripts', 'ucare\enqueue_default_styles' );

// Print styles in header
add_action( 'ucare_head', 'ucare\print_styles' );

// Register default styles
add_action( 'ucare_default_styles', 'ucare\default_styles' );


/**
 * Initialize the style service.
 *
 * @param uCare $ucare The plugin instance.
 *
 * @action ucare_loaded
 *
 * @since 1.4.2
 * @return void
 */
function init_styles( $ucare ) {
    $ucare->set( 'styles', new Styles() );
}


/**
 * Print enqueued styles.
 *
 * @action ucare_head
 *
 * @since 1.4.2
 * @return array|bool
 */
function print_styles() {
    $styles = styles();

    if ( !$styles || did_action( 'ucare_print_styles' ) ) {
        return false;
    }

    do_action( 'ucare_print_styles' );

    $styles->do_items();
    $styles->reset();

    // Get dynamic stylesheet overrides
    get_template( 'dynamic-styles' );

    return $styles->done;
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


/**
 * Register default styles.
 *
 * @action ucare_default_styles
 *
 * @param Styles $styles
 *
 * @since 1.6.0
 * @return void
 */
function default_styles( $styles ) {
    if ( did_action( 'ucare_register_styles' ) ) {
        return;
    }

    $styles->add( 'bootstrap', resolve_url( 'assets/css/bootstrap.min.css' ), null, PLUGIN_VERSION );


    do_action( 'ucare_register_styles' );
}


/**
 * Load default system styles.
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_default_styles() {

    enqueue_fonts();

    ucare_enqueue_style( 'bootstrap' );
    ucare_enqueue_style( 'dropzone',    resolve_url( 'assets/lib/dropzone/css/dropzone.min.css'   ), null, PLUGIN_VERSION );

    ucare_enqueue_style( 'ucare-dropzone', resolve_url( 'assets/css/dropzone.css' ), null, PLUGIN_VERSION );
    ucare_enqueue_style( 'ucare-style',    resolve_url( 'assets/css/style.css'    ), null, PLUGIN_VERSION );

    // Only load these styles in the app
    if ( is_support_page() ) {
        ucare_enqueue_style( 'ucare-dashboard', resolve_url( 'assets/css/dashboard.css' ), null, PLUGIN_VERSION );

        // Libraries
        ucare_enqueue_style( 'scrolling-tabs',  resolve_url( 'assets/lib/scrollingTabs/scrollingTabs.min.css'   ), null, PLUGIN_VERSION );
        ucare_enqueue_style( 'light-gallery',   resolve_url( 'assets/lib/lightGallery/css/lightgallery.min.css' ), null, PLUGIN_VERSION );
    }

}
