<?php

namespace ucare;


add_action( 'admin_bar_menu', 'ucare\admin_bar_ticket_count', 80 );


function admin_bar_ticket_count( \WP_Admin_Bar $admin_bar ) {

    if( current_user_can( 'manage_support' ) ) {

        $count = statprocs\get_unclosed_tickets();

        $item = array(
            'id' => 'ucare_admin_ticket_count',
            'title' => '<span class="ab-icon dashicons dashicons-sos" style="margin-top: 2px;"></span>
                        <span class="ab-label">' . $count . ' </span>',
            'href' => support_page_url()
        );

        $admin_bar->add_node($item);

    }

}
