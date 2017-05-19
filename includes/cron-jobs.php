<?php

namespace ucare\cron;

use ucare\descriptor\Option;

function find_stale_tickets() {

    // send notification 1 day before expiry
    $max_age = get_option( Option::INACTIVE_MAX_AGE, Option\Defaults::INACTIVE_MAX_AGE ) - 1;

    // get the GMT date for n days ago
    $date = gmdate( 'Y-m-d h:i:s', time() - ( 60 * 60 * 24 * $max_age ) );

    $q = new \WP_Query( array(
        'post_type'   => 'ticket',
        'post_status' => 'publish',
        'date_query'  => array(
            'before'    => $date,
            'column'    => 'post_modified_gmt',
            'inclusive' => false
        ),
        'meta_query'  => array(
            'key'       => 'status',
            'value'     => 'closed',
            'compare'   => '!='
        )
    ) );

    foreach( $q->posts as $ticket ) {
        do_action( 'support_ticket_stale', $ticket );
    }
}

function close_stale_tickets() {
    if( get_option( Option::AUTO_CLOSE, Option\Defaults::AUTO_CLOSE ) == 'on' ) {

    }
}