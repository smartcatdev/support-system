<?php
/**
 * Functions for handling plugin updates and migrations.
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


// Perform Migrations
add_action( 'init', 'ucare\upgrade_all', 9 );


/**
 * Perform plugin migrations to next version.
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_all() {

    $current_version = get_option( Options::PLUGIN_VERSION );

    // Nothing to do here
    if ( $current_version == PLUGIN_VERSION ) {
        return;
    }

    if ( $current_version < '1.6.0' ) {
        upgrade_160();
    }

    update_option( Options::PLUGIN_VERSION, PLUGIN_VERSION );

    /**
     * Notify upgrade has completed
     *
     * @since 1.6.0
     */
    do_action( 'ucare_upgraded', PLUGIN_VERSION );

}


/**
 * Execute changes made in version 1.6.0
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_160() {

    // Add new application sub pages
    $parent = get_post( get_option( Options::TEMPLATE_PAGE_ID ) );

    if ( $parent ) {
        $pages = array(
            Options::CREATE_TICKET_PAGE_ID => array(
                'post_title'  => __( 'Create Ticket', 'ucare' ),
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $parent->ID
            ),
            Options::EDIT_PROFILE_PAGE_ID => array(
                'post_title'  => __( 'Edit Profile', 'ucare' ),
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $parent->ID
            ),
            Options::LOGIN_PAGE_ID => array(
                'post_title'  => __( 'Login', 'ucare' ),
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $parent->ID
            )
        );

        // Insert and update template pages
        foreach ( $pages as $option => $data ) {
            $id = wp_insert_post( $data );

            if ( is_numeric( $id ) ) {
                update_option( $option, $id );
            }
        }
    }

    // Reconfigure user capabilities to tighten security for REST API support
    remove_role_capabilities();
    add_role_capabilities();

}
