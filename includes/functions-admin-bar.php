<?php
/**
 * Functions related to the admin bar in the WordPress dashboard.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Add custom link to admin bar
add_action( 'admin_bar_menu', 'ucare\admin_bar_ticket_count', 80 );

// Hide the admin bar for support users
add_action( 'init', fqn( 'hide_admin_bar_if_support_user' ) );

/**
 * Action to a ticket count to the admin bar.
 *
 * @action admin_bar_menu
 *
 * @param \WP_Admin_Bar $admin_bar
 *
 * @since 1.4.2
 * @return void
 */
function admin_bar_ticket_count( \WP_Admin_Bar $admin_bar ) {
    if ( !is_admin() || !current_user_can( 'manage_support' ) ) {
        return;
    }

    $count = statprocs\get_unclosed_tickets();
    $item = array(
        'id'    => 'ucare_admin_ticket_count',
        'title' => strcat(
            '<span class="ab-icon dashicons dashicons-sos" style="margin-top: 2px;"></span>',
            '<span class="ab-label">', absint( $count ), '</span>'
        ),
        'href' => support_page_url()
    );

    $admin_bar->add_node( $item );
}


/**
 * Hide the admin bar if the current user is a support user.
 *
 * @action init
 *
 * @since 1.6.0
 * @return void
 */
function hide_admin_bar_if_support_user() {
    if ( user_is( array( 'support_user', 'support_agent' ) ) ) {
        show_admin_bar( false );
    }
}
