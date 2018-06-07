<?php
/**
 * Functions for handling system email functionality.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

add_action( 'comment_post', 'ucare\send_reply_email' );

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
function send_reply_email( $comment_id ) {
    $comment  = get_comment( $comment_id );
    $ticket   = get_post( $comment->comment_post_ID );

    if ( !$ticket || $ticket->post_type !== 'support_ticket' ) {
        return;
    }

    if ( get_post_meta( $ticket->ID, 'status', true ) === 'closed'  ) {
        return;
    }

    $userdata = get_userdata( $comment->user_id );

    if ( !$userdata ) {
        return; // No comment author
    }

    if ( ucare_is_support_agent( $userdata->ID ) ) {
        $recipient = get_user_by( 'id', $ticket->post_author );
        $template  = get_option( Options::AGENT_REPLY_EMAIL );

    } else if ( ucare_is_support_user( $userdata->ID ) ) {
        $recipient = get_user_by( 'ID', get_post_meta( $ticket->ID, 'agent', true ) );
        $template  = get_option( Options::CUSTOMER_REPLY_EMAIL );
    }

    if ( empty( $template ) || empty( $recipient ) ) {
        return;
    }

    $template_vars = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID,
        'reply'          => $comment->comment_content,
        'agent'          => $comment->comment_author,
        'user'           => $comment->comment_author
    );

    send_email( $template, $recipient->user_email, $template_vars );
}
