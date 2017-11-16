<?php
/**
 * Functions related to the WordPress Settings API.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Register settings
add_action( 'init', 'ucare\register_settings' );

// Add settings fields
add_action( 'admin_init', 'ucare\add_settings_fields', 100 );


/**
 * Action to register settings with the settings API.
 *
 * @action init
 *
 * @since 1.4.2
 * @return void
 */
function register_settings() {

    register_setting( 'uc-advanced', Options::TEMPLATE_PAGE_ID, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id',
    ) );

}


/**
 * Action to add settings fields to be output in the settings page.
 *
 * @action admin_init
 *
 * @since 1.4.2
 * @return void
 */
function add_settings_fields() {

    add_settings_field(
        'ucare-template-page',
        __( 'Support Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-template-page',
            'value'      => get_option( Options::TEMPLATE_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::TEMPLATE_PAGE_ID,
                'class' => 'regular-text'
            ),
            'config' => array(
                'options' => array(
                    array(
                        'title'      => __( 'Select a Page', 'ucare' ),
                        'attributes' => array(
                            'value' => ''
                        )
                    )
                ),
                'wp_query' => array(
                    'post_type' => 'page',
                )
            )
        )
    );

}
