<?php
/**
 * Rest handlers for users endpoints
 *
 * @since 1.7.0
 * @package ucare
 */
namespace ucare;

// Register user endpoints
add_action('rest_api_init', 'ucare\register_user_endpoints' );

/**
 * Register users endpoints
 *
 * @since 1.7.0
 * @return void
 */
function register_user_endpoints() {
    register_rest_route( 'ucare/v1', 'user/verify', array(
        'methods'             => \WP_REST_Server::READABLE,
        'permission_callback' => 'ucare\rest_verify_nonce',
        'callback'            => 'ucare\_rest_user_verify',
        'args'                => array(
            'email' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_email'
            )
        )
    ) );
    register_rest_route( 'ucare/v1', 'user/authenticate', array(
        'methods'             => \WP_REST_Server::CREATABLE,
        'permission_callback' => 'ucare\rest_verify_nonce',
        'callback'            => 'ucare\_rest_user_authenticate',
        'args'                => array(
            'log' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_email'
            ),
            'pwd' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field'
            ),
            'rememberme' => array(
                'sanitize_callback' => 'ucare\sanitize_bool'
            ),
        )
    ) );

    if ( get_option( Options::ALLOW_SIGNUPS ) ) {
        register_rest_route( 'ucare/v1', 'user/register', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'permission_callback' => 'ucare\rest_verify_nonce',
            'callback'            => 'ucare\_rest_user_register',
            'args'                => array(
                'email' => array(
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_email'
                ),
                'first_name' => array(
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'last_name' => array(
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ),
            )
        ) );
    }

    if ( get_option( Options::ENFORCE_TOS ) ) {
        register_rest_route( 'ucare/v1', 'user/accept-tos', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'permission_callback' => 'ucare\rest_verify_nonce',
            'callback'            => 'ucare\_rest_user_accept_tos',
            'args'                => array(
                'email' => array(
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_email'
                )
            )
        ) );
    }

    if ( wp_supports_gdpr() ) {
        register_rest_route( 'ucare/v1', 'user/data-request', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'permission_callback' => 'ucare\rest_verify_support_user',
            'callback'            => 'ucare\_rest_user_data_request',
            'args'                => array(
                'action' => array(
                    'required' => true,
                    'enum'     => array( 'export_personal_data', 'remove_personal_data' )
                )
            )
        ) );
    }
}

/**
 * Create a request to export a user's data
 *
 * @param \WP_REST_Request $request
 *
 * @since 1.7.1
 * @return mixed
 */
function _rest_user_data_request( $request ) {
    $user = wp_get_current_user();
    $request_id = wp_create_user_request( $user->user_email, $request->get_param( 'action' ) );

    if ( is_wp_error( $request_id ) ) {
        return $request_id;
    }

    if ( is_wp_error( wp_send_user_request( $request_id ) ) ) {
        return $request_id;
    }
    $data = array(
        'message' => __( 'Your request was successfully sent. Please check your email for confirmation.', 'ucare' )
    );
    return new \WP_REST_Response( $data, 202 );
}

/**
 * Verify a users email address
 *
 * @param \WP_REST_Request $request
 *
 * @internal
 * @since 1.7.0
 * @return mixed
 */
function _rest_user_verify( $request ) {
    $user = get_user_by( 'email', $request->get_param( 'email' ) );

    if ( $user === false ) {
        return new \WP_Error( 'user_not_found', __( 'User not found', 'ucare' ), array( 'status' => 404 ) );
    }
    $userdata = array(
        'id' => $user->ID,
    );

    if ( get_option( Options::ENFORCE_TOS ) ) {
        $tos_last_updated  = get_option( Options::TOS_REVISION );
        $tos_last_accepted = (int) get_user_meta( $user->ID, 'ucare_tos_accepted', true );
        $userdata['tos_accepted'] = $tos_last_updated < $tos_last_accepted;
    }
    return $userdata;
}

/**
 * Authenticate a user
 *
 * @param \WP_REST_Request $request
 *
 * @internal
 * @since 1.7.0
 * @return mixed
 */
function _rest_user_authenticate( $request ) {
    $credentials = array(
        'user_login'    => $request->get_param( 'log' ),
        'user_password' => $request->get_param( 'pwd' ),
        'rememberme'    => $request->get_param( 'rememberme' )
    );
    $user = wp_signon( $credentials );

    if ( !is_wp_error( $user ) ) {
        $response = new \WP_REST_Response();
        $response->set_headers( array( 'Location' => support_page_url() ) );
        $response->set_status( 204 );
        return $response;
    }

    if ( $user->get_error_code() !== 'incorrect_password' ) {
        return new \WP_Error( 'unknown_error', __( 'Unable to login', 'ucare' ), array( 'status' => 500 ) );
    }
    $token = get_pw_reset_token( get_user_by( 'email', $credentials['user_login'] ) );
    $message = sprintf( ' %1$s <strong><a href="%2$s">%3$s</a></strong>',
        __( 'That password is incorrect.', 'ucare' ), login_page_url( '?password_reset_sent=true&token=' . $token ), __( 'Forgot your password?', 'ucare' )
    );

    return new \WP_Error( 'invalid_password', $message, array( 'status' => 403 ) );
}

/**
 * Register new users
 *
 * @param \WP_REST_Request $request
 *
 * @internal
 * @since 1.7.0
 * @return mixed
 */
function _rest_user_register( $request ) {
    $userdata = array(
        'email'      => $request->get_param( 'email' ),
        'first_name' => $request->get_param( 'first_name' ),
        'last_name'  => $request->get_param( 'last_name' )
    );
    $user = ucare_register_user( $userdata, true, true );

    if ( is_wp_error( $user ) ) {
        return $user;
    }

    if ( $request->get_param( 'tos_accepted' ) ) {
        update_user_meta( $user, 'ucare_tos_accepted', time() );
    }

    /**
     * Send email to create a password
     */
    add_filter('ucare_pw_reset_subject', function () {
        return __( 'Complete Your Registration', 'ucare' );
    });
    ucare_reset_user_password( $userdata['email'] );

    $response = new \WP_REST_Response();
    $response->set_headers( array( 'Location' => create_page_url() ) );
    $response->set_status( 204 );

    return $response;
}

/**
 * Accept the TOS agreement
 *
 * @param \WP_REST_Request $request
 *
 * @internal
 * @since 1.7.0
 * @return mixed
 */
function _rest_user_accept_tos( $request ) {
    $user = get_user_by( 'email', $request->get_param( 'email' ) );

    if ( $user === false ) {
        return new \WP_Error( 'user_not_found', __( 'User not found', 'ucare' ), array( 'status' => 404 ) );
    }

    update_user_meta( $user->ID, 'ucare_tos_accepted', time() );

    $response = new \WP_REST_Response();
    $response->set_status( 204 );
}