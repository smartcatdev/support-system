<?php
/**
 * Functions related to the WordPress Settings API.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Add settings fields
add_action( 'admin_init', 'ucare\add_settings_fields', 100 );


/**
 * Action to add settings fields to be output in the settings page.
 *
 * @action admin_init
 *
 * @since 1.4.2
 * @return void
 */
function add_settings_fields() {


    /**
     *
     * General settings
     */
    add_settings_field(
        'ucare-ecommerce-support',
        __( 'Enable eCommerce Support', 'ucare' ),
        'ucare\render_checkbox',
        'uc-general',
        'uc_general',
        array(
            'description' => __( 'Enable eCommerce support with Easy Digital Downloads or WooCommerce', 'ucare' ),
            'config' => array(
                'is_checked' => sanitize_boolean( get_option( Options::ECOMMERCE ) )
            ),
            'attributes' => array(
                'id'    => 'ucare-ecommerce-support',
                'name'  => Options::ECOMMERCE,
            )
        )
    );


    /**
     *
     * Advanced settings
     */
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

    add_settings_field(
        'ucare-create-ticket-page',
        __( 'Create Ticket Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-create-ticket-page',
            'value'      => get_option( Options::CREATE_TICKET_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::CREATE_TICKET_PAGE_ID,
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

    add_settings_field(
        'ucare-edit-profile-page',
        __( 'Edit Profile Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-edit-profile-page',
            'value'      => get_option( Options::EDIT_PROFILE_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::EDIT_PROFILE_PAGE_ID,
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

    add_settings_field(
        'ucare-login-page',
        __( 'Login Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-login-page',
            'value'      => get_option( Options::LOGIN_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::LOGIN_PAGE_ID,
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
