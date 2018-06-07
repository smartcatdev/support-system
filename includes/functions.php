<?php
/**
 * General use functions and utilities.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;

/**
 * Check if WordPress version support GDPR
 *
 * @global $wp_version
 *
 * @since 1.7.1
 * @return bool
 */
function wp_supports_gdpr() {
    global $wp_version;
    return $wp_version >= '4.6.9';
}

/**
 * Check if the value of a hexidecimal color is light or dark.
 *
 * @param string $color
 *
 * @since 1.1.0
 * @return bool
 */
function hex_is_light( $color ) {
    $hex = str_replace( '#', '', $color );

    $c_r = hexdec( substr( $hex, 0, 2 ) );
    $c_g = hexdec( substr( $hex, 2, 2 ) );
    $c_b = hexdec( substr( $hex, 4, 2 ) );

    $brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

    return $brightness > 155;
}

/**
 * Select a light or dark color based on the value of a hexidecimal
 *
 * @param string $color
 * @param string $dark
 * @param string $light
 *
 * @since 1.1.0
 * @return string
 */
function light_or_dark( $color, $dark = '#000000', $light = '#FFFFFF' ) {
    return hex_is_light( $color ) ? $dark : $light;
}


/**
 * Convert a hexadecimal value to RGB.
 *
 * @param string $hex
 *
 * @since 1.6.0
 * @return array
 */
function hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    $rgb = array ( $r, $g, $b );
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}


/**
 * Safely pluck a value from an object or array.
 *
 * @param object|array $obj
 * @param string       $field
 * @param mixed        $default
 *
 * @since 1.6.0
 * @return mixed
 */
function pluck( $obj, $field, $default = false ) {

    if ( empty( $obj ) ) {
        return $default;
    }

    $data = $obj;

    if ( is_object( $obj ) ) {
        $data = clone $obj;
    }

    $data = (array) $data;

    if ( isset( $data[ $field ] ) ) {
        return $data[ $field ];
    }

    return $default;

}


/**
 * Check if a $_REQUEST nonce is valid.
 *
 * @param        $action
 * @param string $nonce
 *
 * @since 1.4.2
 * @return bool|false|int
 */
function verify_request_nonce( $action, $nonce = '_wpnonce' ) {
    if ( isset( $_REQUEST[ $nonce ] ) ) {
        return wp_verify_nonce( $_REQUEST[ $nonce ], $action );
    }

    return false;
}


/**
 * Get a variable from the $_REQUEST.
 *
 * @param               $var
 * @param string        $default
 * @param callable|null $sanitize
 *
 * @since 1.4.2
 * @return string
 */
function get_var( $var, $default = '', callable $sanitize = null ) {

    if ( isset( $_REQUEST[ $var ] ) ) {
        return !empty( $sanitize ) ? $sanitize( $_REQUEST[ $var ] ) : $_REQUEST[ $var ];
    }

    return $default;
}


/**
 * Get a list of the MIME types that are allowed to be uploaded as attachments.
 *
 * @param null|string $type
 *
 * @since 1.4.2
 * @return array|string
 */
function allowed_mime_types( $type = null ) {

    $file_types  = explode( ',', get_option( Options::FILE_MIME_TYPES, Defaults::FILE_MIME_TYPES   ) );
    $image_types = explode( ',', get_option( Options::IMAGE_MIME_TYPES, Defaults::IMAGE_MIME_TYPES ) );

    if ( $type == 'image' ) {
        return $image_types;
    } else if ( $type == 'file' ) {
        return $file_types;
    }

    return array_merge( $file_types, $image_types );

}


/***********************************************************************************************************************
 *
 * Everything below this comment will be deprecated. Sub namespaces of ucare are no longer supported within core.
 *
 */


namespace ucare\util;

use ucare\Options;
use ucare\Plugin;




function extract_tags( $str, $open, $close ) {
    $matches = array();
    $regex = $pattern =  '~' . preg_quote( $open ) . '(.+)' . preg_quote( $close) . '~misU';

    preg_match_all( $regex, $str, $matches );

    return empty( $matches ) ? false : $matches[1];
}

function encode_code_blocks( $str ) {
    $blocks = extract_tags( $str, '<code>', '</code>' );

    foreach( $blocks as $block ) {
        $str = str_replace( $block, trim(  htmlentities( $block ) ), $str );
    }

    return $str;
}




function filter_defaults() {
    $defaults = array(
        'status' => array(
            'new'               => true,
            'waiting'           => true,
            'opened'            => true,
            'responded'         => true,
            'needs_attention'   => true,
            'closed'            => true
        )
    );

    if( current_user_can( 'manage_support_tickets' ) ) {
        $defaults['status']['closed'] = false;
    }

    return $defaults;
}


function list_agents() {
    $users = get_users();
    $agents = array();

    foreach( $users as $user ) {
        if( $user->has_cap( 'manage_support_tickets' ) ) {
            $agents[ $user->ID ] = $user->display_name;
        }
    }

    return $agents;
}



namespace ucare\statprocs;

function count_tickets( $start, $end, $args = array() ) {
    global $wpdb;

    $start = is_a( $start, 'DateTimeInterface' ) ? $start : date_create( strtotime( $start ) );
    $end =   is_a( $end, 'DateTimeInterface' )   ? $end   : date_create( strtotime( $end ) );

    if( !$start || !$end || $start > $end ) {
        return new \WP_Error( 'invalid date supplied' );
    }

    // Default count by day
    $range = "%Y-%m-%d";
    $interval = new \DateInterval( 'P1D' );
    $diff = $end->diff( $start )->format( '%a' );

    // Get monthly totals if greater than 2 months
    if ( $diff > 62 ) {
        $range = "%Y-%m";
        $interval = new \DateInterval( 'P1M' );
    }

    $values = array($range, $start->format( 'Y-m-d: 00:00:00' ), $end->format( 'Y-m-d 23:59:59' ) );

    if( !empty( $args['closed'] ) ) {

        $q = "SELECT DATE_FORMAT(DATE(m.meta_value), %s ) as d,
          COUNT(m.meta_value) as c
          FROM {$wpdb->posts} p
          INNER JOIN {$wpdb->postmeta} m 
            ON p.ID = m.post_id
          WHERE p.post_type = 'support_ticket'
            AND p.post_status = 'publish' 
            AND m.meta_key = 'closed_date'
            AND (DATE(m.meta_value) BETWEEN DATE( %s ) AND DATE( %s )) ";

    } else {

        $q = "SELECT DATE_FORMAT(DATE(p.post_date), %s ) as d,
          COUNT(p.post_date) as c
          FROM {$wpdb->posts} p
          WHERE p.post_type = 'support_ticket'
            AND p.post_status = 'publish' 
            AND (DATE(p.post_date) BETWEEN DATE( %s ) AND DATE( %s )) ";

    }

    $q .= " GROUP BY d ORDER BY d";

    // Get the data from the query
    $results = $wpdb->get_results( $wpdb->prepare( $q, $values ), ARRAY_A );
    $data = array();

    // All dates in the period at a set interval
    $dates = new \DatePeriod( $start, $interval, clone $end->modify( '+1 second' ) );

    foreach( $dates as $date ) {

        $curr = $date->format( 'Y-m-d' );

        // Set it to 0 by default for this date
        $data[ $curr ] = 0;

        // Loop through each found total
        foreach( $results as $result ) {

            // If the total's date is like the current date set it
            if( strpos( $curr, $result['d'] ) !== false ) {

                $data[ $curr ] = ( int ) $result['c'];

            }

        }

    }

    return $data;
}

function get_unclosed_tickets() {

    global $wpdb;

    $q = 'select ifnull( count(*), 0 ) from ' . $wpdb->prefix . 'posts as a '
            . 'left join ' . $wpdb->prefix . 'postmeta as b '
            . 'on a.ID = b.post_id '
            . 'where a.post_type = "support_ticket" and a.post_status = "publish" '
            . 'and b.meta_key = "status" and b.meta_value != "closed"';

    return $wpdb->get_var( $q );

}

function get_ticket_count( $args = array() ) {

    global $wpdb;

    $defaults = array(
        'status'   => false,
        'priority' => false,
        'agent'    => false,
        'author'   => false
    );

    $args = wp_parse_args( $args, $defaults );


    $q = 'select ifnull( count( DISTINCT a.ID ), 0 ) from ' . $wpdb->prefix . 'posts as a '
            . 'left join ' . $wpdb->prefix . 'postmeta as b '
            . 'on a.ID = b.post_id '
            . 'where a.post_type = "support_ticket" and a.post_status = "publish"';

    if ( $args['status'] ) {
        $q .= ' and b.meta_key = "status" and b.meta_value in ("'. esc_sql( $args['status'] ) . '")';
    }

    if ( $args['priority'] ) {
        $q .= ' and b.meta_key = "priority" and b.meta_value in ("'. esc_sql( $args['priority'] ) . '")';
    }

    if ( $args['agent'] ) {
        $q .= ' and b.meta_key = "agent" and b.meta_value in ("'. esc_sql( $args['agent'] ) . '")';
    }

    if ( $args['author'] ) {
        $q .= " AND a.post_author = " . absint( $args['author'] );
    }

    return $wpdb->get_var( $q );

}

function get_user_assigned( $agents ) {

    $args = array(
        'post_type'     => 'support_ticket',
        'post_status'   => 'publish',
        'meta_query'    => array(
            'relation'  => 'AND',
            array(
                'key'       => 'agent',
                'value'     => $agents,
                'compare'   => 'IN'
            ),
            array(
                'key'       => 'status',
                'value'     => 'closed',
                'compare'   => '!='
            )
        )
    );

    $results = new \WP_Query( $args );

    return $results->found_posts;

}

