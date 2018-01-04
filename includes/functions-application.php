<?php
/**
 * Core application level functions for managing the help desk functionality.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


// Redirect unauthorized users back to the login
add_action( 'template_redirect', 'ucare\auth_redirect' );

// Handle login form submissions
add_action( 'wp_login_failed', 'ucare\handle_login_form' );
add_action( 'authenticate', 'ucare\handle_login_form', 10, 3 );



/**
 * Get the support page URL.
 *
 * @param string $path
 *
 * @since 1.0.0
 * @return string
 */
function support_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::TEMPLATE_PAGE_ID ) ) . $path;
}


/**
 * Get the URL of the create ticket page.
 *
 * @param string $path
 *
 * @since 1.0.0
 * @return string
 */
function create_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::CREATE_TICKET_PAGE_ID ) ) . $path;
}


/**
 * Get the URL of the edit profile page.
 *
 * @param string $path
 *
 * @since 1.6.0
 * @return string
 */
function edit_profile_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::EDIT_PROFILE_PAGE_ID ) ) . $path;
}


/**
 * Get the URL of the login page.
 *
 * @param string $path
 *
 * @since 1.6.0
 * @return string
 */
function login_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::LOGIN_PAGE_ID ) ) . $path;
}


/**
 * Check to see if a page belongs to the support system.
 *
 * @param mixed $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_a_support_page( $page = null ) {

    $page    = get_post( $page );
    $is_page = false;

    if ( $page && $page->post_type == 'page' ) {
        $is_page = $page->ID == get_option( Options::CREATE_TICKET_PAGE_ID ) ||
                   $page->ID == get_option( Options::EDIT_PROFILE_PAGE_ID  ) ||
                   $page->ID == get_option( Options::TEMPLATE_PAGE_ID      ) ||
                   $page->ID == get_option( Options::LOGIN_PAGE_ID         );
    }

    return apply_filters( 'ucare_is_support_page', $is_page );

}


/**
 * Check to see if the current page is the create ticket page.
 *
 * @param null $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_create_ticket_page( $page = null ) {
    return is_page( get_option( Options::CREATE_TICKET_PAGE_ID ), $page );
}


/**
 * Check to see if the current page is the support page.
 *
 * @param null $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_support_page( $page = null ) {
    return is_page( get_option( Options::TEMPLATE_PAGE_ID ), $page );
}


/**
 * Check to see if the current page is the edit profile page.
 *
 * @param null $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_edit_profile_page( $page = null ) {
    return is_page( get_option( Options::EDIT_PROFILE_PAGE_ID ), $page );
}


/**
 * Check to see if the current page is the login page.
 *
 * @param null $page
 *
 * @since 1.6.0
 * @return bool
 */
function is_login_page( $page = null ) {
    return is_page( get_option( Options::LOGIN_PAGE_ID ), $page );
}


/**
 * Check a page ID against an ID.
 *
 * @param $id
 * @param $page
 *
 * @since 1.6.0
 * @return bool
 */
function is_page( $id, $page = null ) {

    $page = get_post( $page );

    if ( $page && $page->post_type == 'page' ) {
        return $id == $page->ID;
    }

    return false;

}


/**
 * Check to see whether a page requires user authentication to access.
 *
 * @param null $page
 *
 * @since 1.6.0
 * @return bool
 */
function is_public_page( $page = null ) {

    $page   = get_post( $page );
    $public = true;

    if ( is_a_support_page( $page ) ) {
        $public = $page->ID == get_option( Options::LOGIN_PAGE_ID );
    }

    return apply_filters( 'ucare_is_public_page', $public, $page );

}


/**
 * Redirect the user if they are logged out or unauthorized.
 *
 * @action template_redirect
 *
 * @since 1.5.1
 * @return void
 */
function auth_redirect() {

    // Send the user to the login page if they are not authenticated
    if ( !is_user_logged_in() && !is_public_page() ) {
        wp_safe_redirect( login_page_url() );

    // Redirect from login form if user is already logged in
    } else if ( is_user_logged_in() && is_login_page() ) {
        wp_safe_redirect( support_page_url() );
    }

}


/**
 * Handle authentication requests from the login form.
 *
 * @action wp_login_failed
 * @action authenticate
 *
 * @param $user
 * @param $username
 * @param $password
 *
 * @since 1.6.0
 * @return void
 */
function handle_login_form( $user = false, $username = '', $password = '' ) {

    // If empty username or password was passed prevent redirect to wp-login
    if ( empty( $username ) && empty( $password ) ) {
        wp_redirect( add_query_arg( 'login', 'empty', wp_get_referer() ) );

    } else if ( !$user ) {
        wp_redirect( add_query_arg( 'login', 'failed', wp_get_referer() ) );
    }
}