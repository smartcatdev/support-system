<?php
/**
 * Functions for handling authentication.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


/**
 * Increment the number of requests from the current IP address.
 *
 * @since 1.6.0
 * @return void
 */
function increment_ip_request_count() {
    if ( ucare_in_dev_mode() ) {
        return;
    }

    $ip = get_ip_address();

    if ( empty( $ip ) ) {
        return;
    }

    $count = get_transient( "ucare_ip_request_count-$ip" );

    if ( empty( (int) $count ) ) {
        $count = 0;
    }

    $count++;

    set_transient( "ucare_ip_request_count-$ip", $count, 15 );

    if ( $count > 3 ) {
        set_transient( "ucare_block_ip-$ip", true, 3600 );
    }
}


/**
 * See if the current request IP is blocked for abusing the system.
 *
 * @since 1.6.0
 * @return bool
 */
function is_ip_blocked() {
    if ( ucare_in_dev_mode() ) {
        return false;
    }

    $ip = get_ip_address();

    if ( empty( $ip ) ) {
        return true; // If for some reason the IP is empty
    }

    return (bool) get_transient( "ucare_block_ip-$ip" );
}


/**
 * Return an error indicating too many attempts have been made.
 *
 * @since 1.6.0
 * @return \WP_Error
 */
function too_many_attempts_error() {
    return new \WP_Error( 'too_many_attempts', __( 'You\'re doing that too much', 'ucare' ), array( 'status' => 400 ) );
}
