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
    } else if ( is_int( $user ) ) {
        $user = get_userdata( $user );
    }

    return is_a( $user, '\WP_User' ) ? $user : false ;

}


/**
 * Get a list of recent tickets for a specific user.
 *
 * @param $user
 * @param array $args
 *
 * @since 1.0.0
 * @return \WP_Query
 */
function get_user_recent_tickets( $user, $args = array() ) {

    $user = get_user( $user );

    $defaults = array(
        'after'   => 'now',
        'before'  => '30 days ago',
        'exclude' => array(),
        'limit'   => -1
    );

    $args = wp_parse_args( $args, $defaults );

    $q = array(
        'post_type'      => 'support_ticket',
        'post_status'    => 'publish',
        'author'         => $user->ID,
        'after'          => $args['after'],
        'before'         => $args['before'],
        'post__not_in'   => $args['exclude'],
        'posts_per_page' => $args['limit'] ?: -1
    );

    return new \WP_Query( $q );

}


/**
 * Count the number of tickets that a user has created.
 *
 * @param int $user_id The ID of the user.
 *
 * @since 1.0.0
 * @return int
 */
function count_user_tickets( $user_id ) {

    global $wpdb;

    $sql = "SELECT COUNT( * )
            FROM $wpdb->posts
            WHERE post_author = %d 
              AND post_type = 'support_ticket'
              AND post_status = 'publish'";

    return $wpdb->get_var( $wpdb->prepare( $sql, $user_id ) );

}