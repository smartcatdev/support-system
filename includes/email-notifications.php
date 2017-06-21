<?php

namespace ucare;

use ucare\descriptor\Option;
use ucare\util\Logger;

function send_email( $template, $recipient, $replace, $args = array() ) {

    $logger = new Logger( 'mail' );
    $sent = false;

    $logger->i( "Sent notification to {$recipient}" );

    // If an email isn't currently being sent
    if( !cache_get( 'email_sending' ) ) {

        cache_put( 'email_sending', true );

        if( get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ) == 'on' ) {

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

        $sender_email = get_option( Option::SENDER_EMAIL, get_option( 'admin_email' ) );
        $sender_name = get_option( Option::SENDER_NAME, Option\Defaults::SENDER_NAME );

        $headers[] = "From: {$sender_name} <{$sender_email}>";

    }

    return $headers;

}

add_action( 'mailer_email_headers', 'ucare\edit_email_headers' );


function email_template_branding() {

    if( is_sending_email() ) {
        echo __( 'Powered by ', 'ucare' ) . '<a href="https://ucaresupport.com/support">uCare Support</a>';
    }

}

add_action( 'email_template_footer', 'ucare\email_template_branding' );


function email_template_vars( $vars ) {

    $support_defaults = array(
        'support_url'  => get_permalink( get_option( Option::TEMPLATE_PAGE_ID ) ),
        'company_name' => get_option( Option::COMPANY_NAME, Option\Defaults::COMPANY_NAME ),
        'company_logo' => get_option( Option::LOGO, Option\Defaults::LOGO )
    );

    return array_merge( $vars, $support_defaults );

}

add_filter( 'mailer_template_vars', 'ucare\email_template_vars' );


function disable_wp_comment_moderation_notices( $emails ) {

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

    return send_email( get_option( Option::PASSWORD_RESET_EMAIL ), $email, $args );

}

add_action( 'support_password_reset_notification', 'ucare\send_password_reset_email', 10, 4 );


function send_user_registration_email( $user_data ) {

    $this->send_template( get_option( Option::WELCOME_EMAIL_TEMPLATE ), $user_data['email'], $user_data );

}

add_action( 'support_user_registered', 'ucare\send_user_registration_email' );


function send_stale_ticket_email( \WP_Post $ticket ) {

    $user = get_user_by( 'ID', $ticket->post_author );

    $replace = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID
    );

    send_email( get_option( Option::INACTIVE_EMAIL ), $user->user_email, $replace );

}

add_action( 'support_mark_ticket_stale', 'ucare\send_stale_ticket_email' );


function send_ticket_created_email( \WP_Post $ticket ) {

    $recipient = wp_get_current_user();

    $template_vars = array(
        'ticket_subject' => $ticket->post_title,
        'ticket_number'  => $ticket->ID,
        'ticket_content' => $ticket->post_content
    );

    send_email( get_option( Option::TICKET_CREATED_EMAIL ), $recipient->user_email, $template_vars );

}

add_action( 'support_ticket_created', 'ucare\send_ticket_created_email' );


function send_ticket_reply_email( $comment_id ) {

    $comment = get_comment( $comment_id );
    $ticket  = get_post( $comment->comment_post_ID );

    // Make sure the ticket is still open
    if( $ticket->post_type == 'support_ticket' && get_post_meta( $ticket->ID, 'status', true ) != 'closed' ) {

        $template_vars = array(
            'ticket_subject' => $ticket->post_title,
            'ticket_number'  => $ticket->ID,
            'reply'          => $comment->comment_content
        );

        // If the current user is an agent, email the customer
        if( current_user_can( 'manage_support_tickets' ) ) {

            $recipient = get_user_by( 'id', $ticket->post_author );

            $template_vars['agent'] = $comment->comment_author;

            send_email( get_option( Option::AGENT_REPLY_EMAIL ), $recipient->user_email, $template_vars );

            // If the user is a customer, notify the assigned agent
        } else if( current_user_can( 'create_support_tickets' ) ) {

            $recipient = get_user_by( 'ID', get_post_meta( $ticket->ID, 'agent', true ) );

            // If the ticket has been assigned to an agent
            if( $recipient ) {

                $customer = get_user_by( 'ID', $comment->user_id );

                $template_vars['user'] = $customer->first_name . ' ' . $customer->last_name;

                send_email( get_option( Option::CUSTOMER_REPLY_EMAIL ), $recipient->user_email, $template_vars );

            }

        }

    }


}

add_action( 'comment_post', 'ucare\send_ticket_reply_email' );


function send_ticket_updated_email( $null, $id, $key, $value, $old ) {

    $post = get_post( $id );

    // Only if the meta value has changed and the post type is support_ticket
    if( $value !== $old && $post->post_type == 'support_ticket' ) {

        $args = array( 'ticket' => $post );

        $template_vars = array(
            'ticket_subject' => $post->post_title,
            'ticket_content' => $post->post_content,
            'ticket_number'  => $post->ID,
        );

        // Notify the user that their ticket has been closed
        if( $key == 'status' && $value == 'closed' ) {

            $recipient = get_user_by('id', $post->post_author );

            $template_vars['ticket_status']  = $value;

            send_email( get_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars, $args );

            // Notify agents if they have been assigned to a ticket only if it hasn't been marked as closed
        } else if( $key == 'agent' && get_post_meta( $id, 'status', true ) != 'closed' ) {

            $recipient = get_user_by( 'ID', $value );

            // Make sure the ticket hans'nt been set to unassigned
            if( $recipient ) {

                $user = get_user_by( 'ID', $post->post_author );

                $template_vars['user'] = $user->first_name . ' ' . $user->last_name;

                send_email( get_option( Option::TICKET_ASSIGNED ), $recipient->user_email, $template_vars, $args );

            }

        }

    }

    return $null;

}

add_action( 'update_post_metadata', 'ucare\send_ticket_updated_email', 10, 5 );
