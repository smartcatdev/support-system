<?php

namespace ucare;


function shortcode_login_form( $args = array() ) {

    $defaults = array(
        'form_id'              => 'loginform',
        'form_class'           => 'support-login-form',
        'show_pw_reset_link'   => true,
        'show_register_link'   => true,
        'logged_in_link_text'  => __( 'Get Support', 'ucare' ),
        'pw_reset_link_text'   => __( 'Forgot Password', 'ucare' ),
        'register_link_text'   => __( 'Register', 'ucare' ),

        'label_password' => __( 'Password', 'ucare' ),
        'label_username' => __( 'Username or Email Address', 'ucare' ),
        'label_remember' => __( 'Remember Me', 'ucare' ),
        'label_log_in'   => __( 'Login', 'ucare' ),

        'id_username' => 'user_login',
        'id_password' => 'user_pass',
        'id_remember' => 'rememberme',
        'id_submit'   => 'wp-submit',

        'value_username' => '',
        'value_remember' => false
    );

    $args = shortcode_atts( $defaults, $args, 'support-login' );

    echo \ucare\util\render( plugin_dir() . '/templates/login-shortcode.php', $args );

}

// Add a shortcode for the login page
add_shortcode( 'support-login', 'ucare\shortcode_login_form' );


function add_login_registration_button( $content, $args ) {

    if ( $args['form_id'] == 'support_login' &&
         get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS ) &&

         // Bypass check fif not passed in args
         ( !isset( $args['show_register_link'] ) || $args['show_register_link'] == true ) ) {

        $link_text = isset( $args['register_link_text'] ) ? $args['register_link_text'] : __( 'Register', 'ucare' );

        $content .= sprintf(
            '<p class="login-register"><a class="button button-primary" href="%1$s">%2$s</a></p>',
            esc_url( support_page_url( '?register=true' ) ),
            esc_html( $link_text )
        );

    }

    return $content;

}

add_action( 'login_form_bottom', 'ucare\add_login_registration_button', 10, 2 );


function add_support_login_field( $content, $args ) {

    if ( $args['form_id'] == 'support_login' ) {
        $content .= '<input type="hidden" name="support_login_form" />';
    }

    return $content;

}

add_action( 'login_form_bottom', 'ucare\add_support_login_field', 10, 2 );
