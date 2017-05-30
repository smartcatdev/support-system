<?php

use ucare\util\Logger;

class migration_1_3_0 implements smartcat\core\Migration {

    private $logger;

    public function __construct() {
        $this->logger = new Logger( 'migration' );
    }

    function version() {
        return '1.3.0';
    }

    function migrate( $plugin ) {

        $this->create_notifications_table();

        $this->logger->i( 'Successfully upgraded to 1.3.0' );

    }

    function create_notifications_table() {
        global $wpdb;

        $wpdb->query(
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
