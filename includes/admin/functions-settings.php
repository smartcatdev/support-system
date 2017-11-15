<?php
/**
 * New place for Settings API Related code. Old School Cool.
 *
 * @since 1.4.2
 */

namespace ucare;


add_action( 'init', 'ucare\register_settings' );

add_action( 'admin_init', 'ucare\add_settings_fields', 100 );


function register_settings() {

    register_setting( 'uc-advanced', Options::TEMPLATE_PAGE_ID, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id',
    ) );

}


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
