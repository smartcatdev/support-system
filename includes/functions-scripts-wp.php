<?php
/**
 * Functions for managing scripts loaded by WordPress.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


//// Load shortcode styles
////add_action( 'wp_enqueue_scripts', 'ucare\enqueue_shortcode_scripts' );
//
//
///**
// * Enqueue styles for the login shortcode.
// *
// * @since 1.4.2
// * @return void
// */
//function enqueue_shortcode_scripts() {
////    wp_enqueue_style( 'ucare-login-form', resolve_url( 'assets/css/login.css' ), null, PLUGIN_VERSION );
//}

// Register login scripts
add_action( 'wp', 'ucare\register_login_scripts' );

// Register login styles
add_action( 'wp', 'ucare\register_login_styles' );

/**
 * Register scripts for the login form
 *
 * @action wp
 *
 * @since 1.7.0
 * @return void
 */
function register_login_scripts() {
    $deps = array(
        'jquery'
    );
    wp_register_script( 'jquery-serialize-json', resolve_url( 'assets/js/jquery-serializejson.js' ), $deps, PLUGIN_VERSION );

    $deps = array(
        'jquery',
        'jquery-serialize-json'
    );
    $l10n = array(
        'rest_url'    => rest_url(),
        'rest_nonce'  => wp_create_nonce( 'wp_rest' ),
        'enforce_tos' => get_option( Options::ENFORCE_TOS )
    );
    wp_register_script( 'ucare-login', resolve_url( 'assets/js/login.js' ), $deps, PLUGIN_VERSION, true );
    wp_localize_script( 'ucare-login', '_ucare_login_l10n', $l10n );
}

/**
 * Register styles for the login form
 *
 * @action wp
 *
 * @since 1.7.0
 * @return void
 */
function register_login_styles() {
    wp_register_style( 'ucare-login', resolve_url( 'assets/css/login.css' ), null, PLUGIN_VERSION );
}
