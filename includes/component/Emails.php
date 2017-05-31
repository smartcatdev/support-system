<?php

namespace ucare\component;

use smartcat\core\AbstractComponent;
use ucare\descriptor\Option;
use ucare\util\Logger;

class Emails extends AbstractComponent {

    private $user;
    private $sending = false;

    private $logger;

    public function __construct() {
        $this->logger = new Logger( 'mail' );
        $this->user = wp_get_current_user();
    }

    public function user_register( $user_data ) {
        $this->send_template( get_option( Option::WELCOME_EMAIL ), $user_data['email'], $user_data );
    }

    public function ticket_updated( $null, $id, $key, $value, $old ) {

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

                $this->send_template( get_option( Option::TICKET_CLOSED_EMAIL ), $recipient->user_email, $template_vars, $args );

            // Notify agents if they have been assigned to a ticket only if it hasn't been marked as closed
            } else if( $key == 'agent' && get_post_meta( $id, 'status', true ) != 'closed' ) {

                $recipient = get_user_by( 'ID', $value );

                // Make sure the ticket hans'nt been set to unassigned
                if( $recipient ) {

                    $user = get_user_by( 'ID', $post->post_author );

                    $template_vars['user'] = $user->first_name . ' ' . $user->last_name;

                    $this->send_template( get_option( Option::TICKET_ASSIGNED_EMAIL ), $recipient->user_email, $template_vars, $args );

                }

            }

        }

        return $null;
    }

    public function ticket_created( $ticket ) {
        $recipient = $this->user->user_email;

        $template_vars = array(
            'ticket_subject' => $ticket->post_title,
            'ticket_number' => $ticket->ID,
            'ticket_content' => $ticket->post_content
        );

        $this->send_template( get_option( Option::TICKET_CREATED_EMAIL ), $recipient, $template_vars );
    }

    public function ticket_reply( $comment_id ) {
        $comment = get_comment( $comment_id );
        $ticket = get_post( $comment->comment_post_ID );

        // Make sure the ticket is still open
        if( $ticket->post_type == 'support_ticket' && get_post_meta( $ticket->ID, 'status', true ) != 'closed' ) {

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number'  => $ticket->ID,
                'reply'          => $comment->comment_content
            );

            // If the current user is an agent, email the customer
            if( current_user_can( 'manage_support_tickets' ) ) {

                $recipient = get_user_by('id', $ticket->post_author );

                $template_vars['agent'] = $comment->comment_author;

                $this->send_template( get_option( Option::AGENT_REPLY_EMAIL ), $recipient->user_email, $template_vars );

            // If the user is a customer, notify the assigned agent
            } else if( current_user_can( 'create_support_tickets' ) ) {

                $recipient = get_user_by( 'ID', get_post_meta( $ticket->ID, 'agent', true ) );

                // If the ticket has been assigned to an agent
                if( $recipient ) {

                    $customer = get_user_by( 'ID', $comment->user_id );

                    $template_vars['user'] = $customer->first_name . ' ' . $customer->last_name;

                    $this->send_template( get_option( Option::CUSTOMER_REPLY_EMAIL ), $recipient->user_email, $template_vars );

                }

            }

        }

    }

    public function disable_wp_comment_notifications( $emails, $comment_id ) {
        $comment = get_comment( $comment_id );
        $ticket = get_post( $comment->comment_post_ID );

        if( $ticket->post_type == 'support_ticket' ) {
            $emails = array();
        }

        return $emails;
    }

    public function email_headers( $headers ) {
        if( $this->sending ) {
            $sender_email = get_option( Option::SENDER_EMAIL, get_option( 'admin_email' ) );
            $sender_name = get_option( Option::SENDER_NAME, Option\Defaults::SENDER_NAME );

            $headers[] = "From: {$sender_name} <{$sender_email}>";
        }

        return $headers;
    }

    private function send_template( $template, $recipient, $template_vars, $args = array() ) {

        $this->sending = true;

        $this->logger->i( "Sent notification to {$recipient}" );

        if( get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ) == 'on' ) {
            return \smartcat\mail\send_template( $template, $recipient, $template_vars, $args );
        } else {
            return false;
        }

    }

    public function email_template_branding() {
        if( $this->sending ) {
            echo __( 'Powered by ', 'ucare' ) . '<a href="https://ucaresupport.com/support">uCare Support</a>';
        }
    }

    public function email_template_vars( $vars ) {
        $support_defaults = array(
            'support_url' => get_permalink( get_option( Option::TEMPLATE_PAGE_ID ) ),
            'company_name' => get_option( Option::COMPANY_NAME, Option\Defaults::COMPANY_NAME ),
            'company_logo' => get_option( Option::LOGO, Option\Defaults::LOGO )
        );

        return array_merge( $vars, $support_defaults );
    }

    public function password_reset( $true, $email, $password, $user ) {
        return $this->send_template( get_option( Option::PASSWORD_RESET_EMAIL ), $email, array(
            'password'       => $password,
            'username'       => $user->user_login,
            'first_name'     => $user->first_name,
            'last_name'      => $user->last_name,
            'full_name'      => $user->first_name . ' ' . $user->last_name,
            'email'          => $email
        ) );
    }

    public function stale_ticket( $ticket ) {
        $user = get_user_by( 'ID', $ticket->post_author );

        $replace = array(
            'ticket_subject' => $ticket->post_title,
            'ticket_number'  => $ticket->ID
        );

        $this->send_template( get_option( Option::INACTIVE_EMAIL ), $user->user_email, $replace );
    }

    public function subscribed_hooks() {
        return array(
            'support_password_reset_notification' => array( 'password_reset', 1, 4 ),
            'support_user_registered'             => array( 'user_register' ),
            'support_mark_ticket_stale'           => array( 'stale_ticket' ),
            'support_ticket_created'              => array( 'ticket_created' ),
            'comment_post'                        => array( 'ticket_reply' ),
            'update_post_metadata'                => array( 'ticket_updated', 10, 5 ),

            'mailer_email_headers'                => array( 'email_headers' ),
            'email_template_footer'               => array( 'email_template_branding' ),
            'mailer_template_vars'                => array( 'email_template_vars' ),

            'comment_notification_recipients'     => array( 'disable_wp_comment_notifications', 10, 2 ),
            'comment_moderation_recipients'       => array( 'disable_wp_comment_notifications', 10, 2 )
        );
    }
}