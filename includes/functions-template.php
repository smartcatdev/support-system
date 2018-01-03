<?php
/**
 * Functions for managing templates.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


add_action( 'init', 'ucare\register_menu_locations' );

add_filter( 'template_include', 'ucare\include_page_template' );


function register_menu_locations() {

    $locations = array(
        'ucare_header_navbar' => __( 'uCare Navigation Menu', 'cdemo' ),
    );

    register_nav_menus( $locations );
}


/**
 * Include a custom page template.
 *
 * @filter template_include
 *
 * @param $template
 *
 * @since 1.0.0
 * @return string
 */
function include_page_template( $template ) {

    // Help Desk page
    if ( is_page( get_option( Options::TEMPLATE_PAGE_ID ) ) ) {
        $template = get_template( 'app', null, false );

    // Create Ticket page
    } else if ( is_page( get_option( Options::CREATE_TICKET_PAGE_ID ) ) ) {
        $template = get_template( 'page-create-ticket', null, false );

    // Edit Profile page
    } else if ( is_page( get_option( Options::EDIT_PROFILE_PAGE_ID ) ) ) {
        $template = get_template( 'page-edit-profile', null, false );
    }

    return $template;

}


/**
 * Get the support system header.
 *
 * @param array $args
 *
 * @since 1.5.1
 * @return void
 */
function get_header( $args = array() ) {
    get_template( 'header', $args );
}


/**
 * Get the support system footer.
 *
 * @param array $args
 *
 * @since 1.5.1
 * @return void
 */
function get_footer( $args = array() ) {
    get_template( 'footer', $args );
}


/**
 * Get the main navigation bar.
 *
 * @since 1.0.0
 * @return void
 */
function get_navbar() {

    // Only show navbar if user is logged in
    if ( is_user_logged_in() && current_user_can( 'use_support' ) ) {

        // Output before nav
        do_action( 'ucare_before_navbar' );

        // Get the navbar template
        get_template( 'navbar' );

        // Allow output after nav
        do_action( 'ucare_after_navbar' );

    }

}
