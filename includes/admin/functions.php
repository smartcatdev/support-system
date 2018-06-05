<?php
/**
 * General functions for the WordPress admin.
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;

// Add message to head
add_action( 'admin_notices', fqn( 'admin_marketing_notification' ) );

// Redirect support users
add_action( 'admin_init', fqn( 'support_user_admin_redirect' ) );


/**
 * Displays a marketing notification in the admin area.
 *
 * @action admin_notices
 *
 * @since 1.6.0
 * @return void
 */
function admin_marketing_notification() {
    if ( get_current_screen()->parent_base !== 'ucare_support' ) {
        return;
    }

    $message = sc_marketing_message( Marketing::ADMIN_NOTIFICATION, false );

    if ( empty( $message ) ) {
        return;
    }

    printf( '<div class="notice notice-success">%s</div>', $message );
}


/**
 * Redirect support users if they try to access the admin directly.
 *
 * @action admin_init
 *
 * @since 1.6.0
 * @return void
 */
function support_user_admin_redirect() {
    $roles = array(
        'support_user',
        'support_agent'
    );

    if ( get_option( Options::ADMIN_REDIRECT ) && !wp_doing_ajax() && user_is( $roles ) ) {
        wp_redirect( home_url() );
    }
}