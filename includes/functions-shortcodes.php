<?php
/**
 * Functions for handling shortcodes.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Add a shortcode for the login page
add_shortcode( 'support-login', 'ucare\support_login_form' );


/**
 * Output a login form for the support page.
 *
 * @param array $args
 * @param bool  $echo
 *
 * @since 1.4.2
 * @return string
 */
function support_login_form( $args = array(), $echo = true ) {

    $defaults = array(
        'form_id'              => 'loginform',
        'form_class'           => 'support-login-form',
        'form_title'           => __( 'Support Login', 'ucare' ),
        'show_pw_reset_link'   => true,
        'show_register_link'   => true,
        'logged_in_link_text'  => __( 'Get Support', 'ucare' ),
        'pw_reset_link_text'   => __( 'Forgot Password', 'ucare' ),
        'register_link_text'   => __( 'Register', 'ucare' ),

        'label_password'       => __( 'Password', 'ucare' ),
        'label_username'       => __( 'Username or Email Address', 'ucare' ),
        'label_remember'       => __( 'Remember Me', 'ucare' ),
        'label_log_in'         => __( 'Login', 'ucare' ),

        'id_username'          => 'user_login',
        'id_password'          => 'user_pass',
        'id_remember'          => 'rememberme',
        'id_submit'            => 'wp-submit',

        'value_username'       => '',
        'value_remember'       => false
    );

    $output = buffer_template( 'shortcode-login', shortcode_atts( $defaults, $args, 'support-login' ) );

    if ( $echo ) {
        echo $output;
    }

    return $output;

}
