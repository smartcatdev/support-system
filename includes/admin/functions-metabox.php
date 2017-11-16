<?php
/**
 * Functions for handling admin metaboxes.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Save the ticket category metabox
add_action( 'save_post_support_ticket', 'ucare\save_ticket_category_metabox' );


/**
 * Callback for adding metaboxes to support_ticket.
 *
 * @since 1.4.2
 * @return void
 */
function add_support_ticket_metaboxes() {

    // Remove the original category metabox
    remove_meta_box( 'tagsdiv-ticket_category', 'support_ticket', 'side' );

    // Add custom category metabox
    add_meta_box( 'ticket-category', __( 'Category', 'ucare' ), 'ucare\render_ticket_category_metabox', 'support_ticket', 'side' );

}


/**
 * Callback to output the ticket category metabox.
 *
 * @param \WP_Post $post The post.
 *
 * @since 1.4.2
 * @return void
 */
function render_ticket_category_metabox( \WP_Post $post ) {

    $args = array(
        'taxonomy'   => 'ticket_category',
        'hide_empty' => false
    );

    $categories = get_terms( $args );
    $selected   = current( wp_get_post_terms( $post->ID, 'ticket_category' ) );

    $field = array(
        'description' => __( 'Set the category topic for the ticket', 'ucare' ),
        'value'       => $selected ? $selected->term_id : '',
        'attributes'  => array(
            'name'  => 'ticket_category',
            'class' => 'regular-text'
        ),
        'config'      => array(
            'options' => array(
                array(
                    'title' => __( 'Select a Category' ),
                    'attributes' => array(
                        'value' => ''
                    )
                )
            )
        )
    );

    foreach ( $categories as $category ) {

        $option = array(
            'title'      => $category->name,
            'attributes' => array(
                'value' => $category->term_id
            )
        );

        array_push( $field['config']['options'], $option );

    }

    render_select_box( $field );
    wp_nonce_field( 'set_ticket_category', 'ticket_category_nonce' );

}


/**
 * Action to save the ticket category metabox.
 *
 * @action save_post_support_ticket
 *
 * @param integer $post_id The ID of the post being saved.
 *
 * @since 1.4.2
 * @return void
 */
function save_ticket_category_metabox( $post_id ) {

    if ( verify_request_nonce( 'set_ticket_category', 'ticket_category_nonce' ) ) {

        $term = get_term( $_POST['ticket_category'], 'ticket_category' );

        if ( $term ) {
            wp_set_post_terms( $post_id, array( $term->term_id ), 'ticket_category' );
        }

    }

}
