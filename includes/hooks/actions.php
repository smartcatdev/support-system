<?php

namespace ucare\hooks;

use ucare\descriptor\Option;

function admin_page_header() {
    include_once \ucare\plugin_dir() . '/templates/admin-header.php';
}

function admin_page_sidebar() {
    include_once \ucare\plugin_dir() . '/templates/admin-sidebar.php';
}

function ticket_updated( $null, $id, $key, $value ) {

    global $wpdb;

    if( get_post_type( $id ) == 'support_ticket' && $key == 'status' ) {

        $q = "UPDATE {$wpdb->posts}
              SET post_modified = %s, post_modified_gmt = %s
              WHERE ID = %d ";

        $q = $wpdb->prepare( $q, array( current_time( 'mysql' ), current_time( 'mysql', 1 ), $id ) );

        $wpdb->query( $q );

        delete_post_meta( $id, 'stale' );

        if( $value == 'closed' ) {

            update_post_meta( $id, 'closed_date', current_time( 'mysql' ) );
            update_post_meta( $id, 'closed_by', wp_get_current_user()->ID );

        }

    }
}

function comment_save( $id ) {

    $post = get_post( get_comment( $id )->comment_post_ID );

    if( $post->post_type == 'support_ticket' ) {

        $status = get_post_meta( $post->ID, 'status', true );

        if( current_user_can( 'manage_support_tickets' ) ) {

            if( $status != 'closed' ) {
                update_post_meta( $post->ID, 'status', 'waiting' );
            }

        } elseif( $status != 'new' && $status != 'closed' ) {

            update_post_meta( $post->ID, 'status', 'responded' );

        }

    }

}

function mark_stale_tickets() {

    // Calculate max age as n days
    $max_age = get_option( Option::INACTIVE_MAX_AGE, Option\Defaults::INACTIVE_MAX_AGE );

    // Current server time
    $time = current_time( 'timestamp', 1 );

    // Get the GMT date for n days ago
    $date = $time - ( 60 * 60 * 24 * $max_age );

    // The date when the ticket will be considered expired
    $expires = $time + ( 60 * 60 * 24 );

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
                'before'    => date( 'Y-m-d 23:59:59', $date ),
                'column'    => 'post_modified_gmt'
            )
        )
    ) );

    error_log( $q->post_count . ' tickets have been marked stale' );

    foreach( $q->posts as $ticket ) {

        // Mark the post as stale
        add_post_meta( $ticket->ID, 'stale', date( 'Y-m-d H:i:s', $expires ) );

        // Fire an action to handle ticket going stale
        do_action( 'support_mark_ticket_stale', $ticket );

    }

}

function close_stale_tickets() {

    if( get_option( Option::AUTO_CLOSE, Option\Defaults::AUTO_CLOSE ) == 'on' ) {

        // Get all stale tickets
        $q = new \WP_Query( array(
            'posts_per_page' => -1,
            'post_type'      => 'support_ticket',
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => 'stale',
                    'value'   => current_time( 'mysql', 1 ),
                    'type'    => 'DATETIME',
                    'compare' => '<='
                )
            )
        ) );

        error_log( $q->post_count . ' tickets have been automatically closed' );

        foreach( $q->posts as $ticket ) {

            // Mark the ticket as closed and delete stale status
            update_post_meta( $ticket->ID, 'status', 'closed' );

            // overwrite the user ID to nobody
            update_post_meta( $ticket->ID, 'closed_by', -1 );
            delete_post_meta( $ticket->ID, 'stale' );

            // Fire an action to handle ticket going stale
            do_action( 'support_autoclose_ticket', $ticket );

        }
    }

}
