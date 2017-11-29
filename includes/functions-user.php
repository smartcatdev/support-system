<?php

namespace ucare;

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
