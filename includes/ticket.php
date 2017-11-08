<?php

namespace ucare;

function ticket_properties_updated( $null, $id, $key, $value ) {

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

// Update the ticket modified on status changes
add_action( 'update_post_metadata', 'ucare\ticket_properties_updated', 10, 4 );


function set_default_ticket_meta( $post_id, $post, $update ) {

    $defaults = array(
        'priority' => 0
    );

    if( !$update ) {

        foreach( $defaults as $key => $value ) {
            add_post_meta( $post_id, $key, $value );
        }

    }

}

add_action( 'wp_insert_post', 'ucare\set_default_ticket_meta', 10, 3 );


function get_recent_tickets( $args = array() ) {

    $defaults = array(
        'author'  => '',
        'after'   => 'now',
        'before'  => '30 days ago',
        'exclude' => array()
    );

    $args = wp_parse_args( $args, $defaults );


    $q = array(
        'post_type'    => 'support_ticket',
        'post_status'  => 'publish',
        'author'       => $args['author'],
        'after'        => $args['after'],
        'before'       => $args['before'],
        'post__not_in' => $args['exclude']
    );

    return new \WP_Query( $q );

}