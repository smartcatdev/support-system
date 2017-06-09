<?php

use ucare\descriptor\Option;

class migration_1_2_1 implements \smartcat\core\Migration {

    private $plugin;

    function version() {
        return '1.2.1';
    }

    function migrate( $plugin )  {

        $this->plugin = $plugin;

        $this->create_email_template();
        $this->create_log_table();

        update_option( Option::LOGGING_ENABLED, Option\Defaults::LOGGING_ENABLED );

        return array( 'success' => true, 'message' => 'uCare has been successfully upgraded to version 1.2.1' );

    }

    function create_log_table() {

        global $wpdb;

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}ucare_logs (
                id              INT PRIMARY KEY AUTO_INCREMENT,
                class           CHAR(1),
                tag             VARCHAR(30),
                event_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                message         TEXT
            )"
        );
    }

    function create_email_template() {

        $id = wp_insert_post(
            array(
                'post_type'     => 'email_template',
                'post_status'   => 'publish',
                'post_title'    => __( 'You have a ticket awaiting action', \ucare\PLUGIN_ID ),
                'post_content'  => file_get_contents( $this->plugin->dir() . 'emails/ticket-close-warning.html' )
            )
        );

        if( $id ) {
            update_post_meta( $id, 'styles', file_get_contents( $this->plugin->dir() . 'emails/default-style.css' ) );
            add_option( Option::INACTIVE_EMAIL, $id );
        }

    }

}

return new migration_1_2_1();