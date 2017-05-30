<?php

namespace ucare\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use ucare\descriptor\Option;
use ucare\util\Logger;

class Notifications extends AbstractComponent {

    private $user;
    private $sending = false;

    private $logger;

    public function __construct() {
        $this->logger = new Logger( 'mail' );
    }

    public function start() {
        $this->user = wp_get_current_user();
    }

    public function user_register( $user_data ) {
        $this->send_template( get_option( Option::WELCOME_EMAIL_TEMPLATE ), $user_data['email'], $user_data );
    }

    public function ticket_updated( $null, $ticket_id, $key, $value ) {
        if( $key == 'status' && $value == 'closed' ) {
            $ticket = get_post( $ticket_id );
            $recipient = get_user_by('id', $ticket->post_author );
            $args = array( 'ticket' => $ticket );

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number' => $ticket->ID,
                'ticket_status' => $value
            );

            $this->send_template( get_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars, $args );
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

        $this->send_template( get_option( Option::CREATED_EMAIL_TEMPLATE ), $recipient, $template_vars );
    }

    public function ticket_reply( $comment_id ) {
        if( current_user_can( 'manage_support_tickets' ) ) {
            $comment = get_comment( $comment_id );
            $ticket = get_post( $comment->comment_post_ID );
            $recipient = get_user_by('id', $ticket->post_author );

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number'  => $ticket->ID,
                'agent'          => $comment->comment_author,
                'reply'          => $comment->comment_content
            );

            $this->send_template( get_option( Option::REPLY_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars );
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
//            $forward_address = get_option( Option::FORWARD_EMAIL, Option\Defaults::FORWARD_EMAIL );
            $sender_email = get_option( Option::SENDER_EMAIL, get_option( 'admin_email' ) );
            $sender_name = get_option( Option::SENDER_NAME, Option\Defaults::SENDER_NAME );

//            if ( !empty( $forward_address ) ) {
//                $headers[] = 'CC:' . $forward_address;
//            }

            $headers[] = "From: {$sender_name} <{$sender_email}>";
        }

        return $headers;
    }

    private function send_template( $template, $recipient, $template_vars, $args = array() ) {

        $this->sending = true;

        $this->logger->i( "Sent notification to {$recipient}" );

        if( get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ) == 'on' ) {
            return Mailer::send_template( $template, $recipient, $template_vars, $args );
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
            'support_user_registered' => array( 'user_register' ),
            'support_mark_ticket_stale' => array( 'stale_ticket' ),
            'support_ticket_created' => array( 'ticket_created' ),
            'comment_post' => array( 'ticket_reply' ),
            'update_post_metadata' => array( 'ticket_updated', 10, 4 ),

            'mailer_email_headers' => array( 'email_headers' ),
            'email_template_footer' => array( 'email_template_branding' ),
            'mailer_template_vars' => array( 'email_template_vars' ),

            'comment_notification_recipients' => array( 'disable_wp_comment_notifications', 10, 2 ),
            'comment_moderation_recipients' => array( 'disable_wp_comment_notifications', 10, 2 )
        );
    }
}