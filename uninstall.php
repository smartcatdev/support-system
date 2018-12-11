<?php
/**
 * Plugin uninstall script.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;


if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die(); // Die if accessed directly
}


// Pull in the plugin class and boot the plugin
require_once dirname( __FILE__ ) . '/plugin.php';  ucare();


if ( get_option( Options::NUKE ) ) {

    /**
     *
     * Delete all options
     */
    ucare_drop_options( Options::class );

    /**
     *
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
     *
     * Drop custom tables
     */
    drop_custom_tables();

    /**
     *
     * Clear scheduled crons
     */
    wp_clear_scheduled_hook( 'ucare_cron_stale_tickets' );
    wp_clear_scheduled_hook( 'ucare_cron_close_tickets' );
}

