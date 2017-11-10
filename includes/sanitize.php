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
