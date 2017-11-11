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
