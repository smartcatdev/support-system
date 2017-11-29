<?php

namespace ucare;

use ucare\Options;
use ucare\util\Logger;

function mark_stale_tickets() {

    $logger = new Logger( 'cron' );

    // Calculate max age as n days
    $max_age = get_option( Options::INACTIVE_MAX_AGE, Defaults::INACTIVE_MAX_AGE );

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

    $logger->i( $q->post_count . _n( ' ticket', ' tickets', $q->post_count ) . _n( ' has', ' have', $q->post_count ) . '  been marked stale' );

    foreach( $q->posts as $ticket ) {

        // Mark the post as stale
        add_post_meta( $ticket->ID, 'stale', date( 'Y-m-d H:i:s', $expires ) );

        // Fire an action to handle ticket going stale
        do_action( 'support_mark_ticket_stale', $ticket );

    }

}

add_action( 'ucare_cron_stale_tickets', 'ucare\mark_stale_tickets' );


function close_stale_tickets() {

    $logger = new Logger( 'cron' );

    if( get_option( Options::AUTO_CLOSE ) === 'on' ) {

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
                ),
                array(
                    'key'     => 'status',
                    'value'   => 'waiting'
                )
            )
        ) );

        $logger->i( $q->post_count . _n( ' ticket', ' tickets', $q->post_count ) . _n( ' has', ' have', $q->post_count ) . ' been automatically closed' );

        foreach( $q->posts as $ticket ) {

            // Mark the ticket as closed and delete stale status
            update_post_meta( $ticket->ID, 'status', 'closed' );

            // overwrite the user ID to nobody
            update_post_meta( $ticket->ID, 'closed_by', -1 );
            delete_post_meta( $ticket->ID, 'stale' );

            // Fire an action to handle ticket going stale
            do_action( 'support_autoclose_ticket', $ticket );

        }
    } else {

        $logger->i( 'Ticket auto-closing is disabled, please re-enable if you wish for tickets to be closed automatically' );

    }

}

add_action( 'ucare_cron_close_tickets', 'ucare\close_stale_tickets' );


function check_extension_licenses() {

    $plugin = Plugin::get_plugin( PLUGIN_ID );
    $activations = $plugin->get_activations();

    $notices = get_option( Options::EXTENSION_LICENSE_NOTICES, array() );

    foreach( $activations as $id => $activation ) {

        $license_data = get_license( $id );

        if( $license_data ) {

            if( $license_data['license'] !== 'valid' ) {

                delete_option( $activation['status_option'] );
                delete_option( $activation['expire_option'] );

                $notices[ $id ] = __( 'Your license for ' . $activation['item_name'] . ' has expired.', 'ucare' );

            } else {

                update_option( $activation['expire_option'], $license_data['expires'] );

            }

        }

    }

    update_option( Options::EXTENSION_LICENSE_NOTICES, $notices );

}

add_action( 'ucare_check_extension_licenses', 'ucare\check_extension_licenses' );
