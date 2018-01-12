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

    if ( current_user_can( 'manage_support' ) ) {
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
}
