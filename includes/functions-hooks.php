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


add_action( 'comment_post', 'ucare\new_comment', 20, 3 );

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