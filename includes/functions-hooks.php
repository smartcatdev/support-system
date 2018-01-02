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


add_action( 'wp_insert_post', 'ucare\ucare_new_ticket', 20, 3 );
add_action( 'comment_post', 'ucare\ucare_new_comment', 20, 3 );


/**
 * 
 * Runs when new ticket is created or when existing ticket is updated
 * 
 * @action wp_insert_post
 * @since 1.5
 * 
 * @param Int $ticket_id
 * @param \WP_Post $ticket
 * @param boolean $update
 */
function ucare_new_ticket( $ticket_id, $ticket, $update ) {
    
    // Ensure we're dealing with a ucare ticket
    if ( $ticket->post_type !== 'support_ticket' ) {
        return;
    }
    
    if ( $update ) {
        do_action( 'support_ticket_updated', $ticket );
    } else {
        do_action( 'support_ticket_created', $ticket );
    }
    
    return;
    
} 

/**
 * 
 * Runs when a new ticket comment has been posted
 * 
 * @action comment_post
 * @since 1.5
 * 
 * @param Int $comment_id
 * @param Int $approved - 1 if approved, 0 if not approved
 * @param array $data
 * @return void
 */
function ucare_new_comment( $comment_id, $approved, $data ) {
    
    $ticket = get_post( $data[ 'comment_post_ID' ] );
    
    // Check if comment has been approved
    // Ensure we're dealing with a ucare ticket
    if( ! $approved || $ticket->post_type !== 'support_ticket' ) {
        return;
    }

    $comment = get_comment( $comment_id );
    
    do_action( 'support_ticket_reply', $comment, $ticket );
    
}