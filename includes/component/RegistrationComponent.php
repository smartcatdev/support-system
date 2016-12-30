<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\core\HookSubscriber;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

class RegistrationComponent extends AbstractComponent implements HookSubscriber {

    public function start() {
        $this->plugin->add_api_subscriber( $this );
    }

    public function register_user() {
        $form = include Plugin::resource_dir( $this->plugin->name() ) . 'config/register_user_form.php';

        if( $form->is_valid() ) {
            $data = $form->data;
            $password = wp_generate_password();

            $user_id = wp_insert_user(
                array(
                    'user_login'    => sanitize_title( $data['first_name'] . ' ' . $data['last_name'] ),
                    'user_email'    => $data['email'],
                    'first_name'    => $data['first_name'],
                    'last_name'     => $data['last_name'],
                    'role'          => 'support_user',
                    'user_pass'     => $password
                )
            );

            add_filter( 'replace_email_template_vars', function( $vars ) use ( $password ) {
                $vars['password'] = $password;

                return $vars;
            } );

            do_action( 'smartcat_send_mail', get_option( Option::WELCOME_EMAIL_TEMPLATE ), $data['email'] );

            wp_set_auth_cookie( $user_id );
            wp_send_json_success();
        } else {
            wp_send_json_error( $form->errors );
        }
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_nopriv_support_register_user' => array( 'register_user' )
        );
    }
}