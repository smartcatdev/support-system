<?php
/**
 * Functions for extension licensing.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

/**
 * Get license data for all registered extensions.
 *
 * @param string|array $ids
 *
 * @since 1.6.0
 * @return array
 */
function get_licensing_data( $ids = '' ) {
    $extensions = ucare_get_license_manager()->get_extensions();
    $license_data = array();

    if ( empty( $extensions ) ) {
        return $license_data;
    }

    foreach ( $extensions as $id => $extension ) {
        if ( !empty( $ids ) && !in_array( $id, (array) $ids ) ) {
            continue; // Skip licenses that don't match
        }

        $expiration = get_option( $extension['options']['expiration'] );

        if ( $expiration ) {
            if ( $expiration === 'lifetime' ) {
                $expiration = __( 'This product has a lifetime license', 'ucare' );
            } else {
                $expiration = date_i18n( get_option( 'date_format' ), strtotime( $expiration ) );
            }
        }

        $data = array(
            'id'         => $id,
            'item_name'  => $extension['item_name'],
            'expiration' => $expiration ? $expiration : '',
            'status'     => get_option( $extension['options']['status'] ),
            'key'        => trim( get_option( $extension['options']['license'] ) )
        );

        $license_data[] = $data;
    }

    return $license_data;
}
