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




/**
 * 
 * @action wp_insert_post
 * 
 * @param Int $ticket_id
 * @param WP_Post $ticket
 * @param boolean $update
 */
function ucare_new_ticket( $ticket_id, $ticket, $update ) {
    
    // Ensure we're dealing with a ucare ticket
    if( ! $ticket->post_type === 'support_ticket' ) {
        return;
    }
    
    error_log( 'ucare_new_ticket runs' );
    
} 