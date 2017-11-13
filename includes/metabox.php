<?php
/**
 * Metabox related code here
 *
 * @since 1.4.2
 */

namespace ucare;


function add_support_ticket_metaboxes() {

    // Remove the original category metabox
    remove_meta_box( 'tagsdiv-ticket_category', 'support_ticket', 'side' );

    // Add custom category metabox
    add_meta_box( 'ticket-category', __( 'Category', 'ucare' ), 'ucare\render_ticket_category_metabox', 'support_ticket', 'side' );

}


function render_ticket_category_metabox( $post ) {

    $selected   = current( wp_get_post_terms( $post->ID, 'ticket_category' ) );
    $categories = get_terms( array( 'taxonomy' => 'ticket_category', 'hide_empty' => false ) );

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


function save_ticket_category_metabox( $post_id ) {

    if ( verify_request_nonce( 'set_ticket_category', 'ticket_category_nonce' ) ) {

        $term = get_term( $_POST['ticket_category'], 'ticket_category' );

        if ( $term ) {
            wp_set_post_terms( $post_id, array( $term->term_id ), 'ticket_category' );
        }

    }

}

add_action( 'save_post_support_ticket', 'ucare\save_ticket_category_metabox' );
