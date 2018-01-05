<?php
/**
 * Misc helper code
 *
 * @since 1.4.2
 */
namespace ucare;


/**
 * Helper for get_post_meta that works in the loop.
 *
 * @param string            $key
 * @param bool              $single
 * @param null|int|\WP_Post $post
 * @param mixed             $default
 *
 * @since 1.6.0
 * @return bool|mixed
 */
function get_metadata( $key, $single = true, $post = null, $default = false ) {

    $post = get_post( $post );

    if ( $post ) {
        return get_post_meta( $post->ID, $key, $single );
    }

    return $default;

}


/**
 * Generate a dropdown from an associative array of options.
 *
 * @param array  $options
 * @param string $selected
 * @param array  $attributes
 *
 * @since 1.6.0
 * @return void
 */
function dropdown( $options, $selected = '', $attributes = array() ) {

    $field = array(
        'attributes' => $attributes,
        'value'      => $selected,
        'config'     => array(
            'options' => array()
        )
    );

    foreach ( $options as $value => $label ) {
        $option = array(
            'title'      => $label,
            'attributes' => array(
                'value' => $value
            )
        );

        array_push( $field['config']['options'], $option );
    }

    render_select_box( $field );

}


/**
 * Generate a checkbox field.
 *
 * @param string $name
 * @param string $label
 * @param string $value
 * @param bool   $checked
 * @param array  $attributes
 *
 * @since 1.6.0
 * @return void
 */
function checkbox( $name, $label = '', $checked = false, $value = '', $attributes = array() ) {

    $field = array(
        'attributes'  => $attributes,
        'description' => $label,
        'config'      => array(
            'is_checked' => $checked
        )
    );

    // Overwrite values from $attributes
    $field['attributes']['name']  = $name;
    $field['attributes']['value'] = $value;

    render_checkbox( $field );

}