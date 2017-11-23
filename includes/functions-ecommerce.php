<?php
/**
 * General use functions for providing e-commerce product support.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


/**
 * Check to see if a post is a valid product.
 *
 * @param mixed $post
 *
 * @sice 1.5.1
 * @return bool
 */
function is_product( $post ) {

    $post = get_post( $post );

    // TODO WOO and EDD specific check
    if ( $post ) {
        return true;
    }

    return false;

}
