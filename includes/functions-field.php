<?php
/**
 * Functions for rendering with Field objects.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


/**
 * Inflates an array of arguments into a field object.
 *
 * @param array|Field $field
 *
 * @since 1.4.2
 * @return Field
 */
function maybe_inflate_field( $field ) {

    if ( is_array( $field ) ) {
        return new Field( $field );
    }

    return $field;

}


/**
 * Map list of agents to options use by \ucare\render_select_box()
 *
 * @param string $cap   The minimum capability for the user.
 * @param string $field The field to use as the value attribute. Default is 'id'.
 *
 * @since 1.0.0
 * @return array
 */
function map_users_to_select_options( $cap = 'use_support', $field = 'ID' ) {

    $options = array();
    $agents  = get_users_with_cap( $cap );

    foreach ( $agents as $agent ) {

        $option = array(
            'title'      => $agent->data->display_name,
            'attributes' => array(
                'value' => $agent->data->$field
            )
        );

        array_push( $options, $option );

    }

    return $options;

}


/**
 * Map list of categories to options used by \ucare\render_select_box().
 *
 * @param string $taxonomy
 *
 * @since 1.5.1
 * @return array
 */
function map_taxonomy_to_select_options( $taxonomy ) {

    $options    = array();
    $categories = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );

    foreach ( $categories as $category ) {

        $option = array(
            'title'      => $category->name,
            'attributes' => array(
                'value' => $category->term_id
            )
        );

        array_push( $options, $option );

    }

    return $options;

}


/**
 * Map a list of posts to options used by \ucare\render_select_box().
 *
 * @param $post_type
 *
 * @since 1.5.1
 * @return array
 */
function map_posts_to_select_options( $post_type ) {

    $options = array();
    $posts   = get_posts( array( 'post_type' => $post_type ) );

    foreach ( $posts as $post ) {

        $option = array(
            'title'      => $post->post_title,
            'attributes' => array(
                'value' => $post->ID
            )
        );

        array_push( $options, $option );

    }

    return $options;

}


/**
 * Output a checkbox field.
 *
 * @param array|Field $field
 *
 * @since 1.4.2
 * @return void
 */
function render_checkbox( $field ) {

    $field = maybe_inflate_field( $field );

    $checked    = isset( $field->config['checked'] )    ? $field->config['checked']    : true;
    $is_checked = isset( $field->config['is_checked'] ) ? $field->config['is_checked'] : false;

    echo '<label>' .
            '<input type="checkbox" ' . parse_attributes( $field->attributes ) .
                checked( $checked, $is_checked, false ) . ' /> ' .
                esc_html( $field->description ?: '' ) .
         '</label>';

}


/**
 * Render a text field.
 *
 * @param array|Field $field
 *
 * @since 1.4.2
 * @return void
 */
function render_text_field( $field ) {

    $field = maybe_inflate_field( $field );

    $defaults = array(
        'type' => 'text'
    );

    $attrs = wp_parse_args( $field->attributes, $defaults );

    echo '<input ' . parse_attributes( $attrs) . ' />';

    if ( !empty( $field->description ) ) {
        echo '<p class="description">' . esc_html( $field->description ) . '</p>';
    }

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




/**
 * Output the users drop down in the create ticket form.
 *
 * @param \WP_Post $ticket
 *
 * @access private
 * @since 1.5.1
 * @return void
 */
function render_create_ticket_users_dropdown( \WP_Post $ticket ) {

    $field = array(
        'value'      => $ticket->post_author,
        'attributes' => array(
            'name'  => 'author',
            'class' => 'form-control'
        ),
        'config' => array(
            'options' => map_users_to_select_options()
        )
    );

    render_select_box( $field );

}


/**
 * Render categories dropdown in the create ticket form.
 *
 * @param \WP_Post $ticket
 *
 * @access private
 * @since 1.5.1
 * @return void
 */
function render_create_ticket_categories_dropdown( \WP_Post $ticket ) {

    $options = array(
        array(
            'title'      => __( 'Select a category', 'ucare' ),
            'attributes' => array(
                'value' => ''
            )
        )
    );

    $value = wp_get_post_terms( $ticket->ID, 'ticket_category' );

    $field = array(
        'value'      => current( $value )->term_id,
        'attributes' => array(
            'name'  => 'categories[]',
            'class' => 'form-control'
        ),
        'config' => array(
            'options' => array_merge( $options, map_taxonomy_to_select_options( 'ticket_category' ) )
        )
    );

    render_select_box( $field );

}


/**
 * Render products dropdown in the create ticket form.
 *
 * @param \WP_Post $ticket
 *
 * @since 1.5.1
 * @return void
 */
function render_create_ticket_products_dropdown( \WP_Post $ticket ) {

    $options = array(
        array(
            'title'      => __( 'Select a product', 'ucare' ),
            'attributes' => array(
                'value' => ''
            )
        )
    );

    if ( UCARE_ECOMMERCE_MODE === 'woo' ) {
        $type = 'product';
    } else if ( UCARE_ECOMMERCE_MODE === 'edd' ) {
        $type = 'download';
    }

    $options = array_merge( $options, map_posts_to_select_options( $type ) );
    $value   = get_post_meta( $ticket->ID, 'product', true );

    $field = array(
        'value'      => $value,
        'attributes' => array(
            'name'  => 'meta[product]',
            'class' => 'form-control'
        ),
        'config' => array(
            'options' => $options
        )
    );

    render_select_box( $field );

}
