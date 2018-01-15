<?php

namespace ucare;

use ucare\Options;
use ucare\util\Logger;

function send_email( $template, $recipient, $replace, $args = array() ) {

    $logger = new Logger( 'mail' );
    $sent = false;

    $logger->i( "Sent notification to {$recipient}" );

    // If an email isn't currently being sent
    if( !cache_get( 'email_sending' ) ) {

        cache_put( 'email_sending', true );

        if( get_option( Options::EMAIL_NOTIFICATIONS, \ucare\Defaults::EMAIL_NOTIFICATIONS ) == 'on' ) {

            $sent = \smartcat\mail\send_template( $template, $recipient, $replace, $args );

        }

        cache_delete( 'email_sending' );

    }

    return $sent;

}

function is_sending_email() {
    return cache_get( 'email_sending' );
}

function edit_email_headers( $headers ) {

    if( is_sending_email() ) {

        $sender_email = get_option( Options::SENDER_EMAIL, get_option( 'admin_email' ) );
        $sender_name = get_option( Options::SENDER_NAME, \ucare\Defaults::SENDER_NAME );

        $headers[] = "From: {$sender_name} <{$sender_email}>";

    }

    return $headers;

}

add_action( 'mailer_email_headers', 'ucare\edit_email_headers' );


function email_template_branding() {

    if ( is_sending_email() ) {
        print_footer_copyright();
    }

}

add_action( 'email_template_footer', 'ucare\email_template_branding' );


function email_template_vars( $vars ) {

    $support_defaults = array(
        'support_url'  => support_page_url(),
        'company_name' => get_option( Options::COMPANY_NAME, \ucare\Defaults::COMPANY_NAME ),
        'company_logo' => get_option( Options::LOGO, \ucare\Defaults::LOGO )
    );

    return array_merge( $vars, $support_defaults );

}

add_filter( 'mailer_template_vars', 'ucare\email_template_vars' );


function disable_wp_comment_moderation_notices( $emails, $comment_id ) {

    $comment = get_comment( $comment_id );
    $ticket = get_post( $comment->comment_post_ID );

    if( $ticket->post_type == 'support_ticket' ) {
        $emails = array();
    }

    return $emails;

}

add_action( 'comment_notification_recipients', 'ucare\disable_wp_comment_moderation_notices', 10, 2 );
add_action( 'comment_moderation_recipients', 'ucare\disable_wp_comment_moderation_notices', 10, 2 );


function send_password_reset_email( $true, $email, $password, $user ) {

    $args = array(
        'password'       => $password,
        'username'       => $user->user_login,
        'first_name'     => $user->first_name,
        'last_name'      => $user->last_name,
        'full_name'      => $user->first_name . ' ' . $user->last_name,
        'email'          => $email
    );

    return send_email( get_option( Options::PASSWORD_RESET_EMAIL ), $email, $args );

}

add_action( 'support_password_reset_notification', 'ucare\send_password_reset_email', 10, 4 );


function send_user_registration_email( $user_data ) {

    send_email( get_option( Options::WELCOME_EMAIL_TEMPLATE ), $user_data['email'], $user_data );

}

add_action( 'support_user_registered', 'ucare\send_user_registration_email' );


function send_stale_ticket_email( \WP_Post $ticket ) {

    $user = get_user_by( 'ID', $ticket->post_author );

    $replace = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID
    );

    send_email( get_option( Options::INACTIVE_EMAIL ), $user->user_email, $replace );

}

add_action( 'support_mark_ticket_stale', 'ucare\send_stale_ticket_email' );


/**
 * 
 * Send ticket created confirmation email to the user that created the ticket
 * 
 * @since 1.0.0
 * @param \WP_Post $ticket
 */
function send_ticket_created_email( \WP_Post $ticket ) {
    
    $recipient = get_user_by( 'ID', $ticket->post_author );

    $template_vars = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID,
        'ticket_content' => $ticket->post_content
    );

    send_email( get_option( Options::TICKET_CREATED_EMAIL ), $recipient->user_email, $template_vars );

}

add_action( 'support_ticket_created', 'ucare\send_ticket_created_email' );


/**
 * 
 * Send ticket created email to the admin
 * 
 * @since 1.0.
 * @param \WP_Post $ticket
 */
function send_new_ticket_email( \WP_Post $ticket ) {

    $recipient = get_option( Options::ADMIN_EMAIL );

    $template_vars = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID,
        'user'           => util\user_full_name( get_user_by( 'id', $ticket->post_author ) ),
        'ticket_content' => $ticket->post_content
    );

    send_email( get_option( Options::NEW_TICKET_ADMIN_EMAIL ), $recipient, $template_vars );

}

add_action( 'support_ticket_created', 'ucare\send_new_ticket_email' );


function send_user_replied_email( $comment_id ) {

    // Check to see if the user has lower privileges than support agents
    if( !current_user_can( 'manage_support_tickets' ) ) {

        $comment = get_comment( $comment_id );
        $ticket  = get_post( $comment->comment_post_ID );

        // Make sure the ticket is still open
        if( $ticket->post_type == 'support_ticket' && get_post_meta( $ticket->ID, 'status', true ) != 'closed' ) {

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number'  => $ticket->ID,
                'reply'          => $comment->comment_content,
                'user'           => $comment->comment_author
            );

            $recipient = get_user_by( 'ID', get_post_meta( $ticket->ID, 'agent', true ) );

            if( $recipient ) {
                send_email( get_option( Options::CUSTOMER_REPLY_EMAIL ), $recipient->user_email, $template_vars );
            }

        }

    }

}

add_action( 'comment_post', 'ucare\send_user_replied_email' );


function send_agent_replied_email( $comment_id ) {

    if( current_user_can( 'manage_support_tickets' ) ) {

        $comment = get_comment( $comment_id );
        $ticket  = get_post( $comment->comment_post_ID );

        // Make sure the ticket is still open
        if( $ticket->post_type == 'support_ticket' && get_post_meta( $ticket->ID, 'status', true ) != 'closed' ) {

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number'  => $ticket->ID,
                'reply'          => $comment->comment_content,
                'agent'          => $comment->comment_author
            );

            $recipient = get_user_by( 'id', $ticket->post_author );

            send_email( get_option( Options::AGENT_REPLY_EMAIL ), $recipient->user_email, $template_vars );

        }

    }

}

add_action( 'comment_post', 'ucare\send_agent_replied_email' );


function send_ticket_updated_email( $null, $id, $key, $value, $old ) {

    $post = get_post( $id );

    // Only if the meta value has changed and the post type is support_ticket
    if( $value !== $old && $post->post_type == 'support_ticket' ) {

        // Notify the user that their ticket has been closed
        if( $key == 'status' && $value == 'closed' ) {

            $recipient = get_user_by('id', $post->post_author );

            $args = array( 'ticket' => $post );

            $template_vars = array(
                'ticket_subject' => $post->post_title,
                'ticket_content' => $post->post_content,
                'ticket_number'  => $post->ID,
                'ticket_status'  => $value
            );

            send_email( get_option( Options::TICKET_CLOSED_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars, $args );

        }

    }

    return $null;

}

add_action( 'update_post_metadata', 'ucare\send_ticket_updated_email', 10, 5 );


function send_ticket_assigned_email( $null, $id, $key, $value, $old ) {

    if( $key == 'agent' && $value != $old && get_post_meta( $id, 'status', true ) != 'closed' ) {

        $post = get_post( $id );
        $recipient = get_user_by( 'ID', $value );

        // Make sure the ticket hasn't been unassigned ( -1 or 0 )
        if( $recipient ) {

            $user = get_user_by( 'ID', $post->post_author );

            $args = array( 'ticket' => $post );

            $template_vars = array(
                'ticket_subject' => $post->post_title,
                'ticket_content' => $post->post_content,
                'ticket_number'  => $post->ID,
                'user'           => util\user_full_name( $user )
            );

            send_email( get_option( Options::TICKET_ASSIGNED ), $recipient->user_email, $template_vars, $args );

        }

    }

    return $null;

}

add_action( 'update_post_metadata', 'ucare\send_ticket_assigned_email', 10, 5 );
