<?php
/**
 * General use functions for providing e-commerce product support.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Get the eCommerce sidebar
add_action( 'ucare_ticket_sidebar_ecommerce', 'ucare\get_ecommerce_sidebar' );


/**
 * Check to see if eCommerce support is enabled.
 *
 * @since 1.4.2
 * @return bool
 */
function is_ecommerce_support_enabled() {
    return defined( 'UCARE_ECOMMERCE_MODE' );
}


/**
 * Action to pull in the default eCommerce sidebar.
 *
 * @param \WP_Post $ticket The current ticket.
 *
 * @action ucare_ticket_sidebar_ecommerce
 *
 * @since 1.4.2
 * @return void
 */
function get_ecommerce_sidebar( \WP_Post $ticket ) {

    $product = get_post( get_post_meta( $ticket->ID, 'product', true ) ?: 0 );

    $args = array(
        'ticket'     => $ticket,
        'product'    => $product ? $product->post_title : __( 'Not Available', 'ucare' ),
        'receipt_id' => get_post_meta( $ticket->ID, 'receipt_id', true )
    );


    // Pull in the template
    get_template( 'sidebar-ecommerce', $args );

}
