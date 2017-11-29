<?php
/**
 * Settings registrations and other settings API related functions.
 *
 * @package ucare
 * @since 1.4.2
 */
namespace ucare;


// Register settings
add_action( 'init', 'ucare\register_settings' );


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

    register_setting( 'uc-general', Options::ECOMMERCE, array(
        'type'    => 'string',
        'default' => Defaults::ECOMMERCE,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

}
