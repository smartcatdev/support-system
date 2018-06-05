<?php
/**
 * Functions for handling the login flow
 *
 * @since 1.7.0
 * @package ucare
 */
namespace ucare;

// Trigger user password reset
add_action( 'init', 'ucare\reset_user_password' );

// Handle password change
add_action( 'admin_post_nopriv_ucare_pw_reset', 'ucare\handle_pw_reset' );

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
 * @param string $token
 *
 * @since 1.7.0
 * @return bool
 */
function check_pw_reset_token( $token ) {
    $parts = decode_pw_reset_token( $token );

    if ( count( $parts ) < 2 ) {
        return false;
    }

    $valid = check_password_reset_key( $parts[0], $parts[1] );

    if ( empty( $valid ) || is_wp_error( $valid ) ) {
        return false;
    }
    return true;
}

/**
 * Decode the PW reset token
 *
 * @param string $token
 *
 * @since 1.7.0
 * @return mixed
 */
function decode_pw_reset_token( $token ) {
    $decoded = base64_decode_maybe( $token );

    if ( !$decoded ) {
        return false;
    }
    return explode( ':', $decoded );
}

/**
 * Handle user PW reset submission
 *
 * @action admin_post_nopriv_ucare_pw_reset
 *
 * @since 1.7.0
 * @return void
 */
function handle_pw_reset() {
    if ( !wp_verify_nonce( pluck( $_POST, '_wpnonce' ), 'reset_pw' ) ) {
        wp_die( __( 'Security check failed', 'ucare' ) );
    }

    if ( empty( $_POST['pw'] ) ) {
        wp_die( __( 'Invalid Password', 'ucare' ) );
    }

    $parts = decode_pw_reset_token( $_POST['token'] );

    if ( count( $parts ) < 2 ) {
        wp_die( __( 'Unable to verify user', 'ucare' ) );
    }

    $user = get_user_by( 'email', $parts[1] );

    if ( !$user ) {
        wp_die( __( 'Invalid User', 'ucare' ) );
    }

    reset_password( $user, $_POST['pw'] );
    wp_safe_redirect( login_page_url() );
}
