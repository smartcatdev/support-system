<?php

namespace ucare;


// Add a shortcode for the login page
add_shortcode( 'support-login', 'ucare\shortcode_login_form' );

add_action( 'wp_enqueue_scripts', 'ucare\enqueue_shortcode_scripts' );


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

    echo buffer_template( 'shortcode-login', $args );

}


function enqueue_shortcode_scripts() {

    wp_enqueue_style( 'ucare-login-form', resolve_url( 'assets/css/login.css' ), null, PLUGIN_VERSION );

}
