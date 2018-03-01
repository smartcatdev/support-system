<?php
/**
 * Functions for managing custom tables in the WordPress database.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

add_action( 'ucare_upgrade_db', fqn( 'create_custom_tables' ) );


/**
 * Create custom tables.
 *
 * @action ucare_upgrade_db
 *
 * @global $wpdb
 *
 * @since 1.6.0
 * @return void
 */
function create_custom_tables() {
    global $wpdb;

    $queries = array(
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}ucare_logs (
          id              INT AUTO_INCREMENT,
          class           CHAR(1),
          tag             VARCHAR(30),
          event_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          message         TEXT,
          PRIMARY KEY(id)
        )"
    );

    foreach ( $queries as $query ) {
        dbDelta( $query );
    }
}


/**
 * Drop custom tables.
 *
 * @global $wpdb
 *
 * @since 1.6.0
 * @return void
 */
function drop_custom_tables() {
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ucare_logs" );
}