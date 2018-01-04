<?php
/**
 * Functions for REST API endpoints.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

// Register endpoints
add_action( 'rest_api_init', 'ucare\rest_register_endpoints' );


/**
 * Register custom endpoints with the REST API.
 *
 * @action rest_api_init
 *
 * @since 1.6.0
 * @return void
 */
function rest_register_endpoints() {

    /**
     * User registration endpoint.
     *
     * @since 1.6.0
     */
    register_rest_route( 'ucare/v1', 'users/register', array(
        'methods' => \WP_REST_Server::CREATABLE,
        'callback' => function ( \WP_REST_Request $request ) {
            return ucare_register_user( $request->get_params(), true );
        }
    ) );

}