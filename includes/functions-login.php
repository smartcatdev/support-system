<?php
/**
 * Functions for handling the login flow
 *
 * @since 1.7.0
 * @package ucare
 */
namespace ucare;

/**
 * Output the login form
 *
 * @since 1.7.0
 * @return void
 */
function login_form() {
    wp_enqueue_script( 'ucare-login' );
    wp_enqueue_style( 'ucare-login' );

    ob_start();
    get_template( 'login-register' );

    echo ob_get_clean();
}
add_shortcode( 'ucare-login', 'ucare\login_form' );