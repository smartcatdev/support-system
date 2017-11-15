<?php
/**
 * Misc helper code
 *
 * @since 1.4.2
 */

namespace ucare;


function verify_request_nonce( $action, $nonce = '_wpnonce' ) {

    if ( isset( $_REQUEST[ $nonce ] ) ) {
        return wp_verify_nonce( $_REQUEST[ $nonce ], $action );
    }

    return false;

}


function get_var( $var, $default = '', callable $sanitize = null ) {

    if ( isset( $_REQUEST[ $var ] ) ) {
        return !empty( $sanitize ) ? $sanitize( $_REQUEST[ $var ] ) : $_REQUEST[ $var ];
    }

    return $default;
}


function parse_attributes( $attributes ) {

    $str = '';

    foreach ( $attributes as $name => $attr ) {
        $str .= $name . '="' . ( is_array( $attr ) ? implode( ' ', $attr ) : esc_attr( $attr ) ) . '" ';
    }

    return $str;

}
