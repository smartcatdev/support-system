<?php
/**
 * Functions for managing scripts loaded by WordPress.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Load shortcode styles
add_action( 'wp_enqueue_scripts', 'ucare\enqueue_shortcode_scripts' );


/**
 * Enqueue styles for the login shortcode.
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_shortcode_scripts() {

    wp_enqueue_style( 'ucare-login-form', resolve_url( 'assets/css/login.css' ), null, PLUGIN_VERSION );

}
