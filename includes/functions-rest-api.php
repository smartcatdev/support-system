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

// Validate the post before insertion
add_filter( 'rest_pre_insert_support_ticket', 'ucare\rest_validate_support_ticket' );


/**
 * Save support ticket meta from the REST API.
 *
 * @param \WP_Post         $post
 * @param \WP_REST_Request $request
 *
 * @action rest_insert_support_ticket
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

    if ( $category ) {
        $cat_term = get_term( absint( $category ), 'ticket_category' );

        if ( $cat_term ) {
            wp_set_post_terms( $post->ID, (array) $cat_term->slug, 'ticket_category' );
        }

    }

// TODO Fix mailer error
    // Notify the ticket has been created
//    do_action( 'support_ticket_created', $post );

}


/**
 * Validate required fields.
 *
 * @param $post
 *
 * @filter rest_pre_insert_support_ticket
 *
 * @since 1.5.1
 * @return \WP_Post|\WP_Error
 */
function rest_validate_support_ticket( $post ) {

    if ( $post->post_status === 'publish' ) {

        // Validate the title is not empty
        if ( empty( $post->post_title ) ) {

            $data = array(
                'status' => 400,
                'field'  => 'title'
            );

            return new \WP_Error( 'empty-title', __( 'Subject cannot be blank', 'ucare' ), $data );

        }


        // Validate the content is not empty
        if ( empty( $post->post_content ) ) {

            $data = array(
                'status' => 400,
                'field'  => 'content'
            );

            return new \WP_Error( 'empty-content', __( 'Description cannot be blank', 'ucare' ), $data );
        }

    }

    return $post;

}
