<?php

namespace ucare\hooks;

function admin_page_header() {
    include_once \ucare\plugin_dir() . '/templates/admin-header.php';
}

function admin_page_sidebar() {
    include_once \ucare\plugin_dir() . '/templates/admin-sidebar.php';
}

function ticket_updated( $null, $id, $key, $value ) {

    global $wpdb;

    if( get_post_type( $id ) == 'support_ticket' ) {

        $q = "UPDATE {$wpdb->posts}
              SET post_modified = %s, post_modified_gmt = %s
              WHERE ID = %d ";

        $q = $wpdb->prepare( $q, array( current_time( 'mysql' ), current_time( 'mysql', 1 ), $id ) );

        $wpdb->query( $q );

        if( $key == 'status' && $value == 'closed' ) {

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
