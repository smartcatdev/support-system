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

            do_action( 'post_support_user_register', $user_data );

            wp_set_auth_cookie( $user_id );
            wp_send_json_success();
        } else {
            wp_send_json_error( $form->errors, 400 );
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
            'wp_ajax_nopriv_support_register_user' => array( 'register_user' )
        ) );
    }
}
