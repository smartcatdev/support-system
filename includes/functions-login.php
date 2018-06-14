<?php
/**
 * Functions for handling the login flow
 *
 * @since 1.7.0
 * @package ucare
 */
namespace ucare;

// Make sure the user is logged out
add_action( 'template_redirect', 'ucare\pw_logout_user' );

// Trigger user password reset
add_action( 'wp', 'ucare\reset_user_password' );

// Handle password change
add_action( 'admin_post_nopriv_ucare_pw_reset', 'ucare\handle_pw_reset' );

/**
 * Output the login form
 *
 * @param array $args
 * @param bool  $echo
 *
 * @since 1.7.0
 * @return string
 */
function login_form( $args = array(), $echo = true ) {
    $defaults = array(
        'login_title'          => get_option( Options::LOGIN_TITLE ),
        'login_subtext'        => get_option( Options::LOGIN_SUBTEXT ),
        'tos_title'            => get_option( Options::TOS_TITLE ),
        'registration_title'   => get_option( Options::REGISTRATION_TITLE ),
        'registration_subtext' => get_option( Options::REGISTRATION_SUBTEXT )
    );

    if ( !is_login_page() ) {
        wp_enqueue_script( 'ucare-login' );
        wp_enqueue_style( 'ucare-login' );
    }

    $out = buffer_template( 'login-register', shortcode_atts( $defaults, $args, 'ucare-login' ) );

    if ( $echo ) {
        echo $out;
    }
    return apply_filters( 'ucare_login_form_html', $out );
}
add_shortcode( 'support-login', 'ucare\login_form' );

/**
 * Log the user out
 *
 * @action template_redirect
 *
 * @since 1.7.0
 * @return void
 */
function pw_logout_user() {
    if ( empty( $_GET['reset_password'] ) ) {
        return;
    }
    wp_logout();
}

/**
 * Handle resetting the user password
 *
 * @action wp
 *
 * @since 1.7.0
 * @return void
 */
function reset_user_password() {
    if ( empty( $_GET['password_reset_sent'] ) || empty( $_GET['token'] ) ) {
        return;
    }
    $decoded = decode_pw_reset_token( $_GET['token'] );

    if ( count( $decoded ) < 2 ) {
        return;
    }
    ucare_reset_user_password( $decoded[1] );
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
 * Create a PW reset token for a user
 *
 * @param \WP_User $user
 *
 * @since 1.7.0
 * @return string
 */
function get_pw_reset_token( $user ) {
    return base64_encode( get_password_reset_key( $user ) . ':' . $user->user_email );
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
    $decoded = base64_decode( $token );

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
