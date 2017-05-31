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

        $id = wp_insert_post(
            array(
                'post_type'     => 'email_template',
                'post_status'   => 'publish',
                'post_title'    => __( 'You have a new notification', 'ucare' ),
                'post_content'  => file_get_contents( $this->plugin->dir() . 'emails/agent-ticket-assigned.html' )
            )
        );

        if( $id ) {
            update_post_meta( $id, 'styles', file_get_contents( $this->plugin->dir() . 'emails/default-style.css' ) );
            add_option( Option::AGENT_NOTIFICATION_EMAIL, $id );
        }

    }

}

return new migration_1_3_0();
