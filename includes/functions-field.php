<?php

namespace ucare;


function maybe_inflate_field( $field ) {

    if ( is_array( $field ) ) {
        return new Field( $field );
    }

    return $field;

}


function render_posts_dropdown( $field ) {

    $field = maybe_inflate_field( $field );

    $defaults = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );

    $args = $field->config['wp_query'];

    $q = new \WP_Query( wp_parse_args( $args, $defaults ) );


    if ( empty( $field->config['options'] ) ) {
        $field->config['options'] = array();
    }

    foreach ( $q->posts as $post ) {

        $option = array(
            'title'      => $post->post_title,
            'attributes' => array(
                'value' => $post->ID
            )
        );

        array_push( $field->config['options'], $option );
    }

    render_select_box( $field );

}


function render_select_box( $field ) {

    $field = maybe_inflate_field( $field );

    if ( !empty( $field->label ) ) {
        echo '<label for="' . esc_attr( $field->attributes['id'] ) . '">' . esc_attr( $field->label ) . '</label>';
    }

    echo '<select ' . parse_attributes( $field->attributes ) . '>';

    foreach ( $field->config['options'] as $option ) {

        echo '<option ' . parse_attributes( $option['attributes'] ) .
             selected( $option['attributes']['value'], $field->value, false ) . '>' . esc_attr( $option['title'] ) .
             '</option>';

    }

    echo '</select>';

    if ( !empty( $field->description ) ) {
        echo '<p class="description">' . esc_html( $field->description ) . '</p>';
    }

}


function render_support_users_dropdown( $field ) {

    $field = maybe_inflate_field( $field );

    $capability = 'use_support';
    $user_query =  isset( $field->config['user_query'] ) ? $field->config['user_query'] : array();

    if ( isset( $field->config['capability'] ) ) {
        $capability = $field->config['capability'];
    }

    if ( empty( $field->config['options'] ) ) {
        $field->config['options'] = array();
    }

    $users = get_users_with_cap( $capability, $user_query );


    foreach ( $users as $user ) {

        $option = array(
            'title'      => $user->display_name,
            'attributes' => array(
                'value' => $user->ID
            )
        );

        array_push( $field->config['options'], $option );

    }

    render_select_box( $field );

}


/**
 * Renders a group of linked checkboxes.
 *
 * @param Field $field The field configuration object.
 *
 * @since 1.4.2
 * @return void
 */
function render_checkbox_group( $field ) {

    $field = maybe_inflate_field( $field );
    $name  = $field->attributes['name'];

    echo '<fieldset ' . parse_attributes( $field->attributes ) . '">';

    foreach ( $field->config['options'] as $option ) {

        echo '<label>' .
                '<input type="checkbox" name="' . esc_attr( $name ) .'[]" ' .
                    parse_attributes( $option['attributes'] ) . ' /> ' . esc_html( $option['title'] ) .
             '</label><br>';

    }

    // Always return an empty array
    echo '<input type="hidden" name="' . esc_attr( $name ) . '[]" value="" />';

    echo '</fieldset>';

}