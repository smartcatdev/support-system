<?php

namespace SmartcatSupport\ajax;

class Registration extends AjaxComponent {

    /**
     * AJAX action to process new user registration. Creates a new user, sends a welcome email and then logs them in.
     *
     * @see config/register_user_form.php.
     * @since 1.0.0
     */
    public function register_user() {
        $form = include $this->plugin->config_dir . '/registration_form.php';

        if( $form->is_valid() ) {
            $user_data = $form->data;
            $user_data['password'] = wp_generate_password();
            $user_data['role'] = 'support_user';

            $user_id = wp_insert_user(
                array(
                    'user_login'    => $user_data['email'],
                    'user_email'    => $user_data['email'],
                    'first_name'    => $user_data['first_name'],
                    'last_name'     => $user_data['last_name'],
                    'role'          => $user_data['role'],
                    'user_pass'     => $user_data['password']
                )
            );

            do_action( 'support_user_registered', $user_data );

            wp_set_auth_cookie( $user_id );
            wp_send_json_success();
        } else {
            wp_send_json_error( $form->errors, 400 );
        }
    }

    public function reset_password() {
        if( isset( $_REQUEST['username'] ) ) {
            $user = false;

            if( is_email( $_REQUEST['username'] ) ) {
                $user = get_user_by( 'email', $_REQUEST['username'] );

                if( !$user ) {
                    $user = get_user_by( 'login', $_REQUEST['username'] );
                }
            }

            if( !$user ) {
                wp_send_json_error( array( 'username' => __( 'That email address could not be found', \SmartcatSupport\PLUGIN_ID ) ), 400 );
            } else {
                $password = wp_generate_password();

                wp_update_user( array(
                    'ID' => $user->ID,
                    'user_pass' => $password
                ) );

                if( apply_filters( 'support_password_reset_notification', true, $user->user_email, $password, $user ) ) {
                    wp_send_json_success( array( 'message' => __( 'Password reset, a temporary password has been sent to your email', \SmartcatSupport\PLUGIN_ID ) ) );
                } else {
                    wp_send_json_error( array( 'message' => __( 'An error has occurred, Please try again later', \SmartcatSupport\PLUGIN_ID ) ), 500 );
                }
            }
        }
    }

    /**
     * Hooks that the Component is subscribed to.
     *
     * @see \smartcat\core\AbstractComponent
     * @see \smartcat\core\HookSubscriber
     * @return array $hooks
     * @since 1.0.0
     */
    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'wp_ajax_nopriv_support_reset_password' => array( 'reset_password' ),
            'wp_ajax_nopriv_support_register_user' => array( 'register_user' )
        ) );
    }
}
