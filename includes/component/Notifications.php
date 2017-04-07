<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;

class Notifications extends AbstractComponent {

    private $user;

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

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number' => $ticket->ID,
                'ticket_status' => $value
            );

            $this->send_template( get_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars );
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

    public function disable_wp_notifications( $emails, $comment_id ) {
        $comment = get_comment( $comment_id );
        $ticket = get_post( $comment->comment_post_ID );

        if( $ticket->post_type == 'support_ticket' ) {
            $emails = array();
        }

        return $emails;
    }

    public function email_headers( $headers ) {
        if( defined( 'SUPPORT_EMAIL_SENDING' ) ) {
            $forward_address = get_option( Option::FORWARD_EMAIL, Option\Defaults::FORWARD_EMAIL );
            $sender_email = get_option( Option::SENDER_EMAIL, get_option( 'admin_email' ) );
            $sender_name = get_option( Option::SENDER_NAME, Option\Defaults::SENDER_NAME );

            if ( !empty( $forward_address ) ) {
                $headers[] = 'CC:' . $forward_address;
            }

            $headers[] = "From: {$sender_name} <{$sender_email}>";
        }

        return $headers;
    }

    private function send_template( $template, $recipient, $template_vars ) {
        define( 'SUPPORT_EMAIL_SENDING', true );

        Mailer::send_template( $template, $recipient, $template_vars );
    }

    public function email_template_branding( $template ) {
        if( defined('SUPPORT_EMAIL_SENDING' ) ) {
            echo __( 'Powered by ', \SmartcatSupport\PLUGIN_ID ) . '<a href="https://ucaresupport.com/support">uCare Support</a>';
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

    public function subscribed_hooks() {
        return array(
            'support_user_registered' => array( 'user_register' ),
            'support_ticket_created' => array( 'ticket_created' ),
            'comment_post' => array( 'ticket_reply' ),
            'update_post_metadata' => array( 'ticket_updated', 10, 4 ),

            'mailer_email_headers' => array( 'email_headers' ),
            'email_template_footer' => array( 'email_template_branding' ),
            'mailer_template_vars' => array( 'email_template_vars' ),

            'comment_notification_recipients' => array( 'disable_wp_notifications', 10, 2 ),
            'comment_moderation_recipients' => array( 'disable_wp_notifications', 10, 2 )
        );
    }
}