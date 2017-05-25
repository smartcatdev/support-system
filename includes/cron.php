<?php

namespace ucare\cron;

use ucare\descriptor\Option;

function stale_tickets() {

    // Calculate max age as n days - 1
    $max_age = get_option( Option::INACTIVE_MAX_AGE, Option\Defaults::INACTIVE_MAX_AGE ) - 1;

    // Get the GMT date for n days ago
    $date = gmdate( 'Y-m-d 23:59:59', time() - ( 60 * 60 * 24 * $max_age ) );

    $q = new \WP_Query( array(
        'posts_per_page' => -1,
        'post_type'      => 'support_ticket',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'stale',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key'     => 'status',
                'value'   => 'closed',
                'compare' => '!='
            )
        ),
        'date_query'     => array(
            array(
                'before'    => $date,
                'column'    => 'post_modified_gmt'
            )
        )
    ) );

    error_log( $q->post_count . ' tickets have been marked stale' );

    // Defer until everything has loaded
    add_action( 'wp_loaded', function () use ( $q ) {

        foreach( $q->posts as $ticket ) {

            // Mark the post as stale
            add_post_meta( $ticket->ID, 'stale', true );

            // Fire an action to handle ticket going stale
            do_action( 'support_mark_ticket_stale', $ticket );

        }

    } );

}

function close_tickets() {

    if( get_option( Option::AUTO_CLOSE, Option\Defaults::AUTO_CLOSE ) == 'on' ) {

        // Get all stale tickets
        $q = new \WP_Query( array(
            'posts_per_page' => -1,
            'post_type'      => 'support_ticket',
            'post_status'    => 'publish',
            'meta_key'       => 'stale',
            'meta_value'     => true
        ) );

        error_log( $q->post_count . ' tickets have been automatically closed' );

        // Defer until everything has loaded
        add_action( 'wp_loaded', function () use( $q ) {

            foreach( $q->posts as $ticket ) {

                // Mark the ticket as closed and delete stale status
                update_post_meta( $ticket->ID, 'status', 'closed' );

                // overwrite the user ID to nobody
                update_post_meta( $ticket->ID, 'closed_by', -1 );
                delete_post_meta( $ticket->ID, 'stale' );

                // Fire an action to handle ticket going stale
                do_action( 'support_autoclose_ticket', $ticket );

            }

        } );
    }
}
