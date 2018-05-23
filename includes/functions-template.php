<?php
/**
 * Functions for managing templates.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Register custom menu location
add_action( 'init', 'ucare\register_menu_locations' );

// Swap template page
add_filter( 'template_include', 'ucare\include_page_template' );


/**
 * Output content below ticket comments widget area.
 *
 * @param \WP_Post $ticket
 * @param bool     $echo
 *
 * @since 1.6.0
 * @return string
 */
function after_comments( $ticket, $echo = false ) {
    /**
     *
     * @since 1.6.0
     */
    $out = clean_html( apply_filters( 'ucare_after_comments', '', $ticket ) );

    if ( $echo ) {
        echo stripslashes( $out );
    }

    return $out;
}



/**
 * Register menu location for primary navigation menu.
 *
 * @since 1.4.2
 * @return void
 */
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
    if ( is_support_page() ) {
        $template = get_template( 'app', null, false );

    // Create Ticket page
    } else if ( is_create_ticket_page() ) {
        $template = get_template( 'page-create-ticket', null, false );

    // Edit Profile page
    } else if ( is_edit_profile_page() ) {
        $template = get_template( 'page-edit-profile', null, false );

    // Login page
    } else if ( is_login_page() ) {
        $template = get_template( 'page-login', null, false );
    }

    return $template;
}


/**
 * Get the main navigation bar.
 *
 * @since 1.0.0
 * @return void
 */
function get_navbar() {
    // Only show navbar if user is logged in
    if ( is_user_logged_in() ) {

        // Output before nav
        do_action( 'ucare_before_navbar' );

        // Get the navbar template
        get_template( 'navbar' );

        // Allow output after nav
        do_action( 'ucare_after_navbar' );
    }
}
