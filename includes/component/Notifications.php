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
        Mailer::send_template( get_option( Option::WELCOME_EMAIL_TEMPLATE ), $user_data['email'], $user_data );
    }

    public function ticket_updated( $ticket ) {
        $recipient = get_user_by('id', $ticket->post_author );

        $template_vars = array(
            'ticket_subject' => $ticket->post_title,
            'ticket_number'  => $ticket->ID,
        );

        $status =  get_post_meta( $ticket->ID, 'status', true );

        if( $status == 'closed' || $status === 'resolved' ) {
            $template_vars['ticket_status'] = $status;
        }

        Mailer::send_template( get_option( Option::UPDATED_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars );
    }

    public function ticket_created( $ticket ) {
        $recipient = $this->user->user_email;

        $template_vars = array(
            'ticket_subject' => $ticket->post_title,
            'ticket_number' => $ticket->ID
        );

        Mailer::send_template( get_option( Option::CREATED_EMAIL_TEMPLATE ), $recipient, $template_vars );
    }

    public function ticket_reply( $comment, $ticket ) {
        if( current_user_can( 'manage_support_tickets' ) ) {
            $recipient = get_user_by('id', $ticket->post_author );

            $template_vars = array(
                'ticket_subject' => $ticket->post_title,
                'ticket_number'  => $ticket->ID,
                'agent'          => $comment->comment_author,
                'reply'          => $comment->comment_content
            );

            Mailer::send_template( get_option( Option::REPLY_EMAIL_TEMPLATE ), $recipient->user_email, $template_vars );
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

    public function subscribed_hooks() {
        return array(
            'support_user_registered' => array( 'user_register' ),
            'support_ticket_created' => array( 'ticket_created' ),
            'support_ticket_reply' => array( 'ticket_reply', 10, 2 ),
            'support_ticket_updated' => array( 'ticket_updated' ),

            'comment_notification_recipients' => array( 'disable_wp_notifications', 10, 2 ),
            'comment_moderation_recipients' => array( 'disable_wp_notifications', 10, 2 )
        );
    }
}