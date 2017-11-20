<?php
/**
 * Functions for controlling the sidebar in single ticket view.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Get the eCommerce sidebar
add_action( 'ucare_ticket_sidebar_ecommerce', 'ucare\get_ecommerce_sidebar' );

// Get the customer sidebar
add_action( 'ucare_ticket_sidebar_customer', 'ucare\get_customer_sidebar' );


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


/**
 * Action to get the customer sidebar
 *
 * @param \WP_Post $ticket The current ticket.
 *
 * @action ucare_ticket_sidebar_customer
 *
 * @since 1.4.2
 * @return void
 */
function get_customer_sidebar( \WP_Post $ticket ) {

    $author = get_userdata( abs( $ticket->post_author ) );

    // Don't include the current ticket in the recent list
    $recent = array(
        'exclude' => array( $ticket->ID )
    );

    $args = array(
        'ticket' => $ticket,
        'author' => $author,
        'total'  => count_user_tickets( $author->ID ),
        'recent' => get_user_recent_tickets( $author, $recent )
    );

    get_template( 'sidebar-customer', $args );

}