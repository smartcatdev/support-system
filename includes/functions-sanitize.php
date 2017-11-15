<?php
/**
 * New place for sanitize callbacks
 *
 * @since 1.4.2
 */

namespace ucare;


function sanitize_post_id( $id ) {

    return get_post( $id ) ? $id : '';

}


function sanitize_agent_id( $id ) {

    $user = get_user_by( 'id', absint( $id ) );

    if ( $user && $user->has_cap( 'manage_support_tickets' ) ) {
        return $id;
    }

    return false;

}


function sanitize_boolean( $val ) {
    return filter_var( $val, FILTER_VALIDATE_BOOLEAN );
}
