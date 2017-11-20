<?php
/**
 * General use functions for providing e-commerce product support.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


/**
 * Check to see if eCommerce support is enabled.
 *
 * @since 1.4.2
 * @return bool
 */
function is_ecommerce_support_enabled() {
    return defined( 'UCARE_ECOMMERCE_MODE' );
}
