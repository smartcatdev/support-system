<?php
/**
 * General sanitize functions.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


/**
 * Sanitize the ID of a post.
 *
 * @param int $id The ID of the post.
 *
 * @since 1.0.0
 * @return mixed THe post ID if true, empty string if false.
 */
function sanitize_post_id( $id ) {
    return get_post( $id ) ? $id : '';
}


/**
 * Sanitize the ID of a user and ensure that the have the manage_support_tickets capability.
 *
 * @param int $id THe ID of the user.
 *
 * @since 1.0.0
 * @return mixed The user ID if true, Empty string if false.
 */
function sanitize_agent_id( $id ) {

    $user = get_user_by( 'id', absint( $id ) );

    if ( $user && $user->has_cap( 'manage_support_tickets' ) ) {
        return $id;
    }

    return '';

}


/**
 * Sanitize truthy values such as 'on', 'yes', 1, true and anything not null.
 *
 * @param mixed $val
 *
 * @since 1.0.0
 * @return mixed
 */
function sanitize_boolean( $val ) {
    return filter_var( $val, FILTER_VALIDATE_BOOLEAN ) == true ? $val : false;
}
