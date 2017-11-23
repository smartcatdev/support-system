<?php
/**
 * Functions for managing WordPress users.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Create a draft ticket for a user
add_action( 'template_redirect', 'ucare\create_draft_ticket' );


/**
 * @param string $cap
 * @param array $args
 *
 * @since 1.4.2
 * @return array
 */
function get_users_with_cap( $cap = 'use_support', $args = array() ) {

    $users = get_users( $args );

    return array_filter( $users, function ( $user ) use ( $cap ) {

        return $user->has_cap( $cap );

    } );

}


/**
 * Get the value of a single field from a \WP_User object
 *
 * @param string $field The field to get
 * @param mixed  $user
 *
 * @since 1.4.2
 * @return mixed
 */
function get_user_field( $field, $user = null ) {

    $user = get_user( $user );

    if ( $user ) {
        return get_the_author_meta( $field, $user->ID );
    }

    return false;

}


/**
 * Gets a \WP_user object. If no user is passed, will default to the currently logged in user.
 * Returns false if no user can be found.
 *
 * @param mixed $user The user to get.
 *
 * @since 1.0.0
 * @return false|\WP_User
 */
function get_user( $user = null ) {

    if ( is_null( $user ) ) {
        $user = wp_get_current_user();
    } else if ( is_numeric( $user ) ) {
        $user = get_userdata( absint( $user ) );
    }

    // Make sure we have a valid support user
    return $user->has_cap( 'use_support' ) ? $user : false;

}


/**
 * Create a post draft for the user when they navigate to the create ticket page.
 *
 * @action template_redirect
 *
 * @since 1.5.1
 * @return void
 */
function create_draft_ticket() {

    if ( !get_user_draft_ticket() ) {

        $user = get_current_user_id();

        $data = array(
            'post_author' => $user,
            'post_type'   => 'support_ticket',
            'post_status' => 'draft'
        );

        $id = wp_insert_post( $data );

        if ( is_numeric( $id ) ) {
            update_user_meta( $user, 'draft_ticket', $id );
        }

    }

}


/**
 * Get the draft ticket for the current user.
 *
 * @since 1.5.1
 * @return int
 */
function get_user_draft_ticket() {

    $draft = get_post( get_user_meta( get_current_user_id(), 'draft_ticket', true ) );

    if ( $draft && $draft->post_status == 'draft' ) {
        return $draft->ID;
    }

    return false;

}
