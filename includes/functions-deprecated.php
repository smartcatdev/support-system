<?php

namespace ucare;


/**
 * @param $key
 * @param $value
 * @deprecated
 */
function cache_put( $key, $value ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    $plugin->$key = $value;

}

/**
 * @param $key
 * @deprecated
 */
function cache_delete( $key ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    unset( $plugin->$key );

}


/**
 * @param $key
 * @param bool $default
 *
 * @return bool
 * @deprecated
 */
function cache_get( $key, $default = false ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    if( isset( $plugin->$key ) ) {
        return $plugin->$key;
    } else {
        return $default;
    }

}


/**
 * @return null
 * @deprecated
 */
function plugin_dir() {
    return Plugin::plugin_dir( PLUGIN_ID );
}

/**
 * @param string $path
 *
 * @return string
 * @deprecated
 */
function plugin_url( $path = '' ) {
    return trailingslashit( Plugin::plugin_url( PLUGIN_ID ) ) . ltrim( $path, '/' );
}


namespace ucare\util;

use ucare\Options;
use ucare\Plugin;


/**
 * @deprecated
 * @return array
 */
function statuses () {
    return array(
        'new'               => __( 'New', 'ucare' ),
        'waiting'           => __( 'Waiting', 'ucare' ),
        'opened'            => __( 'Opened', 'ucare' ),
        'responded'         => __( 'Responded', 'ucare' ),
        'needs_attention'   => __( 'Needs Attention', 'ucare' ),
        'closed'            => __( 'Closed', 'ucare' ),
    );
}


/**
 * @param $user
 *
 * @return string|void
 * @deprecated
 */
function user_full_name( $user ) {

    if( $user ) {
        return $user->first_name . ' ' . $user->last_name;
    }

    return;

}


/**
 * @param $ticket
 * @param string $orderby
 * @param string $order
 * @param string $mime_type
 *
 * @return array
 * @deprecated
 */
function get_attachments( $ticket, $orderby = 'post_date', $order = 'DESC', $mime_type = '' ) {
    $query = new \WP_Query(
        array(
            'post_parent'       => $ticket->ID,
            'post_type'         => 'attachment',
            'post_status'       => 'inherit',
            'orderby'           => $order,
            'order'             => $order,
            'post_mime_type'    => $mime_type
        ) );

    return $query->posts;
}


/**
 * @param $stamp
 *
 * @return mixed|string|void
 * @deprecated
 */
function just_now( $stamp ) {
    $now = date_create();
    $date = date_create( $stamp );

    if( $now->diff( $date )->format( '%i' ) == 0 ) {
        $out = __( 'Just Now', 'ucare' );
    } else {
        $out = __( human_time_diff( strtotime( $stamp ), current_time( 'timestamp' ) ) . ' ago', 'ucare' );
    }

    return $out;
}


/**
 * @param $template
 * @param array $data
 *
 * @return string
 * @deprecated
 */
function render( $template, array $data = array() ) {
    extract($data);
    ob_start();

    include($template);

    return ob_get_clean();
}


/**
 * @deprecated
 */
function author_email( $ticket ) {

    $user = get_user_by( 'ID', $ticket->post_author );

    if( $user ) {

        return $user->user_email;

    }

    return;

}


/**
 * @deprecated
 */
function products() {
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
    $products = array();

    if( get_option( Options::ECOMMERCE, \ucare\Defaults::ECOMMERCE ) ) {
        $post_type = array();

        if ( $plugin->woo_active ) {
            $post_type[] = 'product';
        }

        if ( $plugin->edd_active ) {
            $post_type[] = 'download';
        }

        $post_type = implode('","', $post_type );

        if( !empty( $post_type ) ) {

            global $wpdb;

            $query = 'select ID from ' . $wpdb->prefix . 'posts where post_type in ("' . $post_type . '") and post_status = "publish"';

            $posts = $wpdb->get_results( $query );

            foreach( $posts as $post ) {

                $products[ $post->ID ] = get_the_title( $post->ID );
            }

        }
    }

    return $products;
}


/**
 * @param bool $strict
 *
 * @return bool
 * @deprecated
 */
function ecommerce_enabled( $strict = true ) {
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
    $enabled = false;

    if( get_option( Options::ECOMMERCE, \ucare\Defaults::ECOMMERCE == 'on' ) ) {
        if( $strict && ( $plugin->woo_active || $plugin->edd_active ) ) {
            $enabled = true;
        } else {
            $enabled = true;
        }
    }

    return $enabled;
}

