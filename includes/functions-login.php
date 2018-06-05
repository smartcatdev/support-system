<?php
/**
 * Functions for handling the login flow
 *
 * @since 1.7.0
 * @package ucare
 */
namespace ucare;

//
add_action( 'init', 'ucare\reset_user_password' );

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

/**
 * Handle resetting the user password
 *
 * @action init
 *
 * @since 1.7.0
 * @return void
 */
function reset_user_password() {
    if ( empty( $_GET['password_reset_sent'] ) || empty( $_GET['u'] ) ) {
        return;
    }
    ucare_reset_user_password( $_GET['u'] );
}

/**
 * Validate a user's password reset token
 *
 * @global $wpdb
 *
 * @param $token
 *
 * @since 1.7.0
 * @return bool
 */
function check_pw_reset_token( $token ) {
    $decoded = base64_decode_maybe( $token );

    if ( !$decoded ) {
        return false;
    }

    $parts = explode( ':', $decoded );

    if ( count( $parts ) < 2 ) {
        return false;
    }

    $valid = check_password_reset_key( $parts[0], $parts[1] );

    if ( empty( $valid ) || is_wp_error( $valid ) ) {
        return false;
    }
    return true;
}