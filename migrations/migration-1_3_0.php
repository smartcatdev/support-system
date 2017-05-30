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

        if( $this->create_notifications_table() ) {
            $this->logger->i( 'Successfully upgraded to 1.3.0' );
        } else {
            $this->logger->e( 'Error upgrading to 1.3.0 unable to create table' );
        }

    }

    function create_email_template() {

        $id = wp_insert_post(
            array(
                'post_type'     => 'email_template',
                'post_status'   => 'publish',
                'post_title'    => __( 'You have a new notification', 'ucare' ),
                'post_content'  => file_get_contents( $this->plugin->dir() . 'emails/agent-notification.html' )
            )
        );

        if( $id ) {
            update_post_meta( $id, 'styles', file_get_contents( $this->plugin->dir() . 'emails/default-style.css' ) );
            add_option( Option::AGENT_NOTIFICATION_EMAIL, $id );
        }

    }

    function create_notifications_table() {
        global $wpdb;

        return $wpdb->query(
          "CREATE TABLE IF NOT EXISTS  {$wpdb->prefix}ucare_notifications (
              id          INT PRIMARY KEY AUTO_INCREMENT,
              from_user   INT,
              to_user     INT NOT NULL,
              data        TEXT,
              timestamp   DATETIME DEFAULT CURRENT_TIMESTAMP
          )"
        );

    }

}

return new migration_1_3_0();
