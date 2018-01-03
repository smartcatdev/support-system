<?php
/**
 * Core application level functions for managing the help desk functionality.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


// Redirect unauthorized users back to the login
add_action( 'template_redirect', 'ucare\maybe_do_auth_redirect' );


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
                   $page->ID == get_option( Options::TEMPLATE_PAGE_ID      );
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
 * Redirect the user if they are logged out or unauthorized.
 *
 * @action template_redirect
 *
 * @todo Separate login/registration page
 *
 * @since 1.5.1
 * @return void
 */
function maybe_do_auth_redirect() {

    if ( ( is_create_ticket_page() || is_edit_profile_page() ) &&
         ( !is_user_logged_in()    || !current_user_can( 'use_support' ) ) ) {

        // Redirect back to the help desk url
        wp_safe_redirect( support_page_url() );

    }

}
