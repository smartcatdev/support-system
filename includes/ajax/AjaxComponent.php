<?php

namespace ucare\ajax;


use smartcat\core\AbstractComponent;

abstract class AjaxComponent extends AbstractComponent {

    private $hooks;

    protected function validate_request () {
        check_ajax_referer( 'support_ajax' );
    }

    public function start() {
        if( wp_doing_ajax() && isset( $_REQUEST['action'] ) ) {
            array_filter( $this->hooks, function ( $hook ) {

                if( strpos( $hook, $_REQUEST['action'] ) !== false ) {
                    if( strpos( $hook, 'nopriv' ) === false && !current_user_can( 'use_support' ) ) {
                        wp_die( -1, 401 );
                    }

                    $this->validate_request();

                    return;
                }

            } );
        }
    }

    public function subscribed_hooks( $hooks = array () ) {
        $this->hooks = array_keys( $hooks );

        return $hooks;
    }

    protected function render( $template, array $data = array() ) {
        extract( $data );
        ob_start();

        include( $template );

        return ob_get_clean();
    }
}
