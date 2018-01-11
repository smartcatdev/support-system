<?php
/**
 * Plugin uninstall script.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;


if ( !get_option( Options::DEV_MODE ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die(); // Die if accessed directly
}

global $wpdb;


// Pull in the plugin class and boot the plugin
require_once dirname( __FILE__ ) . '/plugin.php';  ucare();


if ( get_option( Options::NUKE ) ) {

    /**
     * Delete all options
     */
    drop_options( Options::class );


    /**
     * Trash all custom post types
     */
    $args = array(
        'post_type' => array( 'support_ticket', 'email_template' )
    );

	$query = new \WP_Query( $args );

	foreach ( $query->posts as $post ) {
		wp_trash_post( $post->ID );
	}


    /**
     * Drop custom tables
     */
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ucare_logs" );


    /**
     * Clear scheduled crons
     */
    wp_clear_scheduled_hook( 'ucare_cron_stale_tickets' );
    wp_clear_scheduled_hook( 'ucare_cron_close_tickets' );
}


/**
 * Drop an options class
 *
 * @param string $class
 *
 * @internal
 * @since 1.6.0
 * @return void
 */
function drop_options( $class ) {

    if ( class_exists( $class ) || interface_exists( $class ) ) {
        $options = new \ReflectionClass( $class );

        foreach ( $options->getConstants() as $option ) {
            delete_option( $option );
        }
    }

}
