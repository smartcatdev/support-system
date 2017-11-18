<?php
/**
 * General use functions for e-commerce providing e-commerce product support.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


function is_ecommerce_support_enabled() {
    return defined( 'UCARE_ECOMMERCE_MODE' );
}


