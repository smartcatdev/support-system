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

    /**
     * Reset password endpoint.
     *
     * @since 1.6.0
     */
    register_rest_route( 'ucare/v1', 'auth/reset-password', array(
        'methods' => \WP_REST_Server::CREATABLE,
        'callback' => function ( \WP_REST_Request $request ) {
            $reset = ucare_reset_user_password( $request->get_param( 'username' ) );

            if ( is_wp_error( $reset ) ) {
                return $reset;
            }

            $res = array(
                'message' => __( 'Password reset, a temporary password has been sent to your email', 'ucare' )
            );

            return $res;
        }
    ) );

}