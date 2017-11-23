<?php
/**
 * Functions for the WordPress REST API.
 *
 * @since 1.5.1
 * @package ucare
 */
namespace ucare;


// Insert custom meta passed with the REST request
add_action( 'rest_insert_support_ticket', 'ucare\rest_create_support_ticket', 10, 2 );


/**
 * Save support ticket meta from the REST API.
 *
 * @param \WP_Post         $post
 * @param \WP_REST_Request $request
 *
 * @since 1.5.1
 * @return void
 */
function rest_create_support_ticket( \WP_Post $post, \WP_REST_Request $request ) {

    // Insert meta
    $meta = $request->get_param( 'meta' );

    if ( is_array( $meta ) ) {

        if ( !empty( $meta['product'] ) && is_product( $meta['product'] ) ) {
            update_post_meta( $post->ID, 'product', absint( $meta['product'] ) );
        }

        if ( !empty( $meta['receipt_id'] ) ) {
            update_post_meta( $post->ID, 'receipt_id', sanitize_text_field( $meta['receipt_id'] ) );
        }

    }

    // Set the category
    $category = $request->get_param( 'category' );

    if ( term_exists( absint( $category ), 'ticket_category' ) ) {
        wp_set_post_terms( $post->ID, (array) $category, 'ticket_category' );
    }


    // Notify the ticket has been created
    do_action( 'support_ticket_created', $post );

}
