<?php
/**
 * Functions for general formatting of output.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

/**
 * Prints a human readable time difference.
 *
 * @param string $from The starting time (any strtotime() string)
 * @param string $to   The ending time (any strtotime() string)
 * @param bool $echo   Whether the function should echo its output
 *
 * @since 1.4.2
 * @return string
 */
function time_diff( $from, $to = '', $echo = true ) {

    // Add custom output filter
    add_filter( 'human_time_diff', 'ucare\filter_human_time_diff', 10, 2 );

    $diff = human_time_diff( strtotime( $from ), $to ? strtotime( $to ) : '' );

    // Remove output filter
    remove_filter( 'human_time_diff', 'ucare\filter_human_time_diff', 10 );

    if ( $echo ) {
        esc_html_e( $diff );
    }

    return $diff;

}


/**
 * Custom filter output of human_time_diff().
 *
 * @param $since
 * @param $diff
 *
 * @since 1.4.2
 * @return string
 */
function filter_human_time_diff( $since, $diff ) {

    if ( $diff < 60 ) {
        $since = __( 'Seconds ago', 'ucare' );
    } else if ( $diff === 0 ) {
        $since = __( 'Just now', 'ucare' );
    } else {
        $since = sprintf( __( '%s ago', 'ucare' ), $since );
    }

    return $since;

}


/**
 * Get the non-slug readable ticket status.
 *
 * @param string $ticket  The ticket
 * @param string $default Output if status inst found
 * @param bool   $echo    Whether the function should echo
 *
 * @since 1.4.2
 * @return string
 */
function ticket_status( $ticket, $default = 'N/A', $echo = true ) {

    $_status = $default;
    $ticket  = get_post( $ticket );

    if ( $ticket ) {
        $status   = get_post_meta( $ticket->ID, 'status', true );
        $statuses = get_ticket_statuses();

        if ( array_key_exists( $status, $statuses ) ) {
            $_status = $statuses[ $status ];
        }

        if ( $echo ) {
            esc_html_e( $_status );
        }

    }

    return $_status;

}


/**
 * Join pieces of a string together.
 *
 * @since 1.5.1
 * @return string
 */
function strcat() {
    return join( '', func_get_args() );
}


/**
 * Echo an escaped url.
 *
 * @param string     $url
 * @param null|array $protocols
 *
 * @since 1.6.0
 * @return void
 */
function esc_url_e( $url, $protocols = null ) {
    echo esc_url( $url, $protocols );
}
