<?php


namespace SmartcatSupport\ajax;


abstract class EventListener {

    protected function __construct() {}

    public static function init() {
        $instance = new static;
        $instance->register_actions();

        return $instance;
    }

    protected function add_event_listener( $event, $callable, $public, $args = 1 ) {
        
    }

    protected function verify_nonce( $action ) {
        if( !isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], $action ) ) {
            $this->send_error('Error: Nonce verification failed');
        }
    }

    protected function send_success( $data = null ) {
        wp_send_json_success( $data );
    }

    protected function send_error( $data = null ) {
        wp_send_json_error( $data );
    }

    abstract protected function register_actions();
}