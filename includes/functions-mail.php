<?php
/**
 * Functions for handling system email functionality.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


add_action( 'comment_post', 'ucare\send_user_replied_email'  );
add_action( 'comment_post', 'ucare\send_agent_replied_email' );



/**
 * Send email to assigned agent when user has replied to a support ticket.
 *
 * @action comment_post
 *
 * @param int $comment_id
 *
 * @todo Utilize a hook from the API class
 * @since 1.0.0
 * @return void
 */
function send_user_replied_email( $comment_id ) {
    if ( !ucare_is_support_user() ) {
        return;
    }

    $comment = get_comment( $comment_id );
    $ticket  = get_post( $comment->comment_post_ID );

    if ( !$ticket || $ticket->post_type !== 'support_ticket' ) {
        return;
    }

    if ( get_post_meta( $ticket->ID, 'status', true ) === 'closed' ) {
        return;
    }

    $recipient = get_user_by( 'ID', get_post_meta( $ticket->ID, 'agent', true ) );

    if ( !$recipient ) {
        return;
    }

    $template_vars = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID,
        'reply'          => $comment->comment_content,
        'user'           => $comment->comment_author
    );

    send_email( get_option( Options::CUSTOMER_REPLY_EMAIL ), $recipient->user_email, $template_vars );
}


/**
 * Send an email to the support user when an agent has replied to their ticket.
 *
 * @action comment_post
 *
 * @param int $comment_id
 *
 * @todo Utilize a hook from the API class
 * @since 1.0.0
 * @return void
 */
function send_agent_replied_email( $comment_id ) {
    if ( !ucare_is_support_agent() ) {
        return;
    }

    $comment = get_comment( $comment_id );
    $ticket  = get_post( $comment->comment_post_ID );

    if ( !$ticket || $ticket->post_type !== 'support_ticket' ) {
        return;
    }

    if ( get_post_meta( $ticket->ID, 'status', true ) === 'closed'  ) {
        return;
    }

    $recipient = get_user_by( 'id', $ticket->post_author );

    if ( !$recipient ) {
        return;
    }

    $template_vars = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID,
        'reply'          => $comment->comment_content,
        'agent'          => $comment->comment_author
    );

    send_email( get_option( Options::AGENT_REPLY_EMAIL ), $recipient->user_email, $template_vars );
}
