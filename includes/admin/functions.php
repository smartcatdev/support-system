<?php
/**
 * General functions for the WordPress admin.
 *
 * @since 1.6.1
 * @package ucare
 * @subpackage admin
 */
namespace ucare;

// Add message to head
add_action( 'admin_notices', fqn( 'admin_marketing_notification' ) );


/**
 * Displays a marketing notification in the admin area.
 *
 * @action admin_notices
 *
 * @since 1.6.1
 * @return void
 */
function admin_marketing_notification() {
    $screen = get_current_screen();

    if ( $screen->parent_base !== 'ucare_support' ) {
        return;
    }

    $message = sc_marketing_message( 'admin-notification', false );

    if ( empty( $message ) ) {
        return;
    }

    printf( '<div class="notice notice-success">%s</div>', $message );
}
