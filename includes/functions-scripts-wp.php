<?php
/**
 * Functions for managing scripts loaded by WordPress.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

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
