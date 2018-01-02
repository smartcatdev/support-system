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

// Filter REST GET request params based on user capabilities.
add_filter( 'rest_support_ticket_query', 'ucare\rest_filter_support_ticket_query' );

// Filter REST GET request params based on user capabilities.
add_filter( 'rest_attachment_query', 'ucare\rest_filter_attachment_query' );

// Filter out REST GET comments based on user capabilities
add_filter( 'rest_comment_query', 'ucare\rest_filter_comment_query' );


/**
 * Filter comments if user cannot manage support.
 *
 * global $wpdb
 *
 * @param $args
 *
 * @filter rest_comment_query
 *
 * @since 1.5.1
 * @return array
 */
function rest_filter_comment_query( $args ) {

    global $wpdb;

    if ( !ucare_is_support_agent() ) {

        // Prevent selecting tickets that the user is NOT the author of
        $sql = "SELECT ID 
                FROM $wpdb->posts 
                WHERE post_type = 'support_ticket' 
                  AND post_author != %d";

        $args['post__not_in'] = $wpdb->get_col( $wpdb->prepare( $sql, get_current_user_id() ) );

    }

    return $args;

}


/**
 * If the current user cannot manage support tickets, restrict them to tickets that they have created.
 *
 * @param array $args
 *
 * @filter rest_support_ticket_query
 *
 * @since 1.5.1
 * @return array
 */
function rest_filter_support_ticket_query( $args ) {

    $user_id = get_current_user_id();

    if ( $user_id > 0 && !ucare_is_support_agent( $user_id ) ) {
        $args['author'] = $user_id;
    }

    return $args;

}


/**
 * If the user cannot manage tickets, restrict attachments only to ones that belong to tickets they are authors of.
 *
 * @global $wpdb
 *
 * @param array $args
 *
 * @filter rest_support_ticket_query
 *
 * @since 1.5.1
 * @return array
 */
function rest_filter_attachment_query( $args ) {

    global $wpdb;

    if ( !ucare_is_support_agent() ) {

        // Prevent selecting tickets that the user is NOT the author of
        $sql = "SELECT ID 
                FROM $wpdb->posts 
                WHERE post_type = 'support_ticket' 
                  AND post_author != %d";

        $args['post_parent__not_in'] = $wpdb->get_col( $wpdb->prepare( $sql, get_current_user_id() ) );

    }

    return $args;

}

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
