<?php

use ucare\descriptor\Option;
use ucare\util\Logger;

class migration_1_3_0 implements smartcat\core\Migration {

    private $logger;
    private $plugin;

    public function __construct() {
        $this->logger = new Logger( 'migration' );
    }

    function version() {
        return '1.3.0';
    }

    function migrate( $plugin ) {

        $this->plugin = $plugin;

        $this->create_email_template();

        $this->logger->i( 'Upgraded to 1.3.0' );

    }

    function create_email_template() {

        $email_templates = array(
          array(
              'title'   => __( 'You have been assigned a ticket', 'ucare' ),
              'option'  => Option::TICKET_ASSIGNED,
              'content' => file_get_contents( $this->plugin->dir() . 'emails/agent-ticket-assigned.html' )
          ),
            array(
                'title'   => __( 'You have a reply to a ticket that you are assigned to', 'ucare' ),
                'option'  => Option::CUSTOMER_REPLY_EMAI,
                'content' => file_get_contents( $this->plugin->dir() . 'emails/agent-ticket-reply.html' )
            ),
        );

        $default_style = file_get_contents( $this->plugin->dir() . 'emails/default-style.css' );

        foreach( $email_templates as $template ) {

            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => $template['title'],
                    'post_content'  => $template['content']
                )
            );

            if( $id ) {
                update_post_meta( $id, 'styles', $default_style );
                add_option( $template['option'], $id );
            }

        }

    }

}

return new migration_1_3_0();
