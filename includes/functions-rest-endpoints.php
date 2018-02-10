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
        'callback' => 'ucare\rest_handler_register_user'
    ) );

    /**
     * Reset password endpoint.
     *
     * @since 1.6.0
     */
    register_rest_route( 'ucare/v1', 'auth/reset-password', array(
        'methods' => \WP_REST_Server::CREATABLE,
        'callback' => 'ucare\rest_handler_rest_password'
    ) );

    /**
     * Extension licenses endpoint.
     *
     * @since 1.6.1
     */
    register_rest_route( 'ucare/v1', 'extensions/licenses/(?P<id>([0-9a-z-_])+)', array(
        array(
            'methods'  => \WP_REST_Server::CREATABLE,
            'callback' => fqn( 'rest_manage_extension_license' ),
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            }
        ),
        array(
            'methods'  => \WP_REST_Server::READABLE,
            'callback' => fqn( 'rest_check_extension_license' ),
            'permission_callback' => function () {
                return current_user_can( 'manage_options' );
            }
        )
    ) );
}


/**
 * Handler for the user registration endpoint.
 *
 * @param \WP_REST_Request $request
 *
 * @since 1.6.0
 * @return mixed
 */
function rest_handler_register_user( $request ) {
    $user = ucare_register_user( $request->get_params(), true );

    if ( is_wp_error( $user ) ) {
        return $user;
    }

    $response = new \WP_REST_Response();
    $response->set_status( 201 );

    return $response;
}


/**
 * Handler for the reset password endpoint.
 *
 * @param \WP_REST_Request $request
 *
 * @since 1.6.0
 * @return mixed
 */
function rest_handler_rest_password( $request ) {
    $reset = ucare_reset_user_password( $request->get_param( 'username' ) );

    if ( is_wp_error( $reset ) ) {
        return $reset;
    }

    $data = array(
        'message' => __( 'Password reset, a temporary password has been sent to your email', 'ucare' )
    );

    return new \WP_REST_Response( $data, 200 );
}


/**
 * Handler to manage license activations and deactivations.
 *
 * @param \WP_REST_Request $request
 *
 * @since 1.6.1
 * @return mixed
 */
function rest_manage_extension_license( $request ) {
    $id = $request->get_param( 'id' );

    if ( empty( $id ) ) {
        return new \WP_Error( 'invalid_product', __( 'Invalid product', 'ucare' ), array( 'status' => 404 ) );
    }

    $manager = ucare_get_license_manager();

    switch ( $request->get_param( 'action' ) ) {
        case 'activate':
            $success = $manager->activate_license( $id, $request->get_param( 'key' ) );

            if ( is_wp_error( $success ) ) {
                return $success;
            }

            return array( 'message' => __( 'License activated', 'ucare' ) );

        case 'deactivate':
            $success = $manager->deactivate_license( $id );

            if ( is_wp_error( $success ) ) {
                return $success;
            }

            return array( 'message' => __( 'License deactivated', 'ucare' ) );
    }

    return new \WP_Error( 'invalid_action', __( 'Invalid action', 'ucare' ), array( 'status' => 400 ) );
}


/**
 * Check the current status of an extension license.
 *
 * @param \WP_REST_Request $request
 *
 * @since 1.6.1
 * @return mixed
 */
function rest_check_extension_license( $request ) {
    $manager = ucare_get_license_manager();

    $data = $manager->get_license( $request->get_param( 'id' ) );

    if ( is_wp_error( $data ) ) {
        return $data;
    }

    if ( !$data ) {
        return new \WP_Error( 'invalid_product', __( 'Invalid product', 'ucare' ), array( 'status' => 404 ) );
    }

    return $data;
}