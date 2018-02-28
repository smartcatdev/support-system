<?php
/**
 * Functions for managing WordPress users.
 *
 * @package ucare
 * @since 1.6.0
 */
namespace ucare;

// Filter support user caps
add_filter( 'user_has_cap', fqn( 'revoke_user_media_perms' ), 10, 4 );


/**
 * Filter support user capabilities.
 *
 * @action user_has_cap
 *
 * @param $all
 * @param $caps
 * @param $args
 * @param $user
 *
 * @since 1.6.0
 * @return array
 */
function revoke_user_media_perms( $all, $caps, $args, $user ) {
    if ( $args[0] !== 'upload_files' ) {
        return $all;
    }

    if ( in_array( 'use_support', $all ) && in_array( 'upload_files', $all ) ) {
        $all['upload_files'] = false;
    }

    return $all;
}