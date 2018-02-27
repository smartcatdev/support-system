<?php
/**
 * Functions for handling HTTP requests.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


/**
 * Get the request IP address.
 *
 * @since 1.6.0
 * @return mixed
 */
function get_ip_address() {
    if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        return $_SERVER['HTTP_CLIENT_IP'];

    } else if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // To check ip is pass from proxy
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    return $_SERVER['REMOTE_ADDR'];
}
