<?php

namespace SmartcatSupport\ajax;


class Settings extends AjaxComponent {

    /**
     * AJAX action to launch the user settings screen.
     *
     * @since 1.0.0
     */
    public function settings() {
        if( current_user_can( 'view_support_tickets' ) ) {
            wp_send_json( $this->render( $this->plugin->template_dir . '/settings.php' ) );
        }
    }

    /**
     * AJAX action to save the user's settings.
     *
     * @see config/settings_form.php
     * @since 1.0.0
     */
    public function save_settings() {
        $form = include $this->plugin->dir() . '/config/settings_form.php';

        if( $form->is_valid() ) {
            if( !empty( $form->data['new_password'] ) ) {
                if( $form->data['new_password'] == $form->data['confirm_password'] ) {
                    wp_set_password( $form->data['new_password'], wp_get_current_user()->ID );
                }
            }

            wp_update_user(
                array(
                    'ID'         => wp_get_current_user()->ID,
                    'first_name' => $form->data['first_name'],
                    'last_name'  => $form->data['last_name']
                )
            );

            wp_send_json_success( __( 'Settings updated refresh to apply your changes', \SmartcatSupport\PLUGIN_ID ) );

        } else {
            wp_send_json_error( $form->errors );
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
        return array(
            'wp_ajax_support_settings' => array( 'settings' ),
            'wp_ajax_support_save_settings' => array( 'save_settings' ),
        );
    }
}
