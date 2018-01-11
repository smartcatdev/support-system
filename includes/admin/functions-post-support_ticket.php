<?php
/**
 * Admin-side functions for managing the support ticket post type.
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


// Redirect to the app create ticket page
add_action( 'load-post-new.php', 'ucare\_redirect_to_create_ticket' );

// Redirect to the app ticket list
add_action( 'wp', 'ucare\_redirect_to_help_desk' );


/**
 * Redirect admin page requests to the helpdesk application.
 *
 * @action load-post-new.php
 *
 * @internal
 * @since 1.6.0
 * @return void
 */
function _redirect_to_help_desk() {
    $redirect = get_current_screen()->id == 'edit-support_ticket';

    /**
     * Provided for legacy support if the admin dash UI is still required
     *
     * @since 1.6.0
     */
    $redirect = apply_filters( 'ucare_admin_redirect_to_helpdesk', $redirect );

    if ( $redirect ) {
        wp_redirect( support_page_url() );
    }
}


/**
 * Redirect admin page requests to the create ticket page.
 *
 * @action load-post-new.php
 *
 * @internal
 * @since 1.6.0
 * @return void
 */
function _redirect_to_create_ticket() {
    $redirect = get_current_screen()->id == 'support_ticket' ;

    /**
     * Provided for legacy support if the admin dash UI is still required
     *
     * @since 1.6.0
     */
    $redirect = apply_filters( 'ucare_admin_redirect_to_helpdesk', $redirect ) ;

    if ( $redirect ) {
        wp_redirect( create_page_url() );
    }
}
