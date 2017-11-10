<?php
/**
 * Misc helper code
 *
 * @since 1.4.2
 */

namespace ucare;


function parse_attributes( $attributes ) {

    $str = '';

    foreach ( $attributes as $name => $attr ) {
        $str .= $name . '="' . ( is_array( $attr ) ? implode( ' ', $attr ) : esc_attr( $attr ) ) . '" ';
    }

    return $str;

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

    echo '<label for="' . esc_attr( $field->id ) . '"></label>';

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
