<?php
/**
 * Hooks for uCare
 * These hook on to existing core hooks
 * 
 * @since 1.5.0
 * @package ucare
 * 
 */
namespace ucare;


add_action( 'publish_support_ticket', 'ucare\ticket_created', 20, 2 );

add_action( 'wp_insert_post', 'ucare\ticket_updated', 20, 3 );

add_action( 'comment_post', 'ucare\new_comment', 20, 3 );


/**
 * Runs when a ticket is published and is visible from within the application UI.
 *
 * @action publish_support_ticket
 *
 * @param $id
 * @param $ticket
 *
 * @since 1.6.0
 * @return void
 */
function ticket_created( $id, $ticket ) {
    if ( get_post_meta( 'published', $id, true ) ) {
        return; // Only allow created action to run once for a ticket
    }
    update_post_meta( $id, 'published', true );

    /**
     *
     * @since 1.6.0
     */
    do_action( 'support_ticket_created', $ticket, $id );
}


/**
 * 
 * Runs when new ticket is created or when existing ticket is updated
 * 
 * @action wp_insert_post
 * 
 * @param int      $id
 * @param \WP_Post $ticket
 * @param boolean  $update
 *
 * @since 1.5.0
 * @return void
 */
function ticket_updated( $id, $ticket, $update ) {
    // Ensure we're dealing with a ucare ticket
    if ( $ticket->post_type !== 'support_ticket' && $update ) {
        do_action( 'support_ticket_updated', $ticket, $id );
    }
}


/**
 * Runs when a new ticket comment has been posted
 * 
 * @action comment_post
 *
 * @param int   $comment_id
 * @param int   $approved   1 if approved, 0 if not approved
 * @param array $data
 *
 * @since 1.5.0
 * @return void
 */
function new_comment( $comment_id, $approved, $data ) {
    $ticket = get_post( $data[ 'comment_post_ID' ] );
    
    // Check if comment has been approved
    // Ensure we're dealing with a ucare ticket
    if( !$approved || $ticket->post_type !== 'support_ticket' ) {
        return;
    }

    $comment = get_comment( $comment_id );
    
    do_action( 'support_ticket_reply', $comment, $ticket );
}