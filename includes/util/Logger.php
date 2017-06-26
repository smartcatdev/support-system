<?php


namespace ucare\util;


use ucare\Options;

class Logger {

    const INFO = 'i';
    const ERROR = 'e';
    const DEBUG = 'd';
    const WARN = 'w';

    public $type;

    public function __construct($tag = 'general' ) {
        $this->type = $tag;
    }

    protected function insert_log( $class, $message ) {

        if( get_option( Options::LOGGING_ENABLED ) == 'on' ) {

            global $wpdb;

            $q = "INSERT INTO {$wpdb->prefix}ucare_logs VALUES( NULL, %s, %s, %s, %s )";

            $wpdb->query( $wpdb->prepare( $q, array( $class, $this->type, current_time( 'mysql', 1 ), $message ) ) );


        }

    }

    public function log( $class, $message ) {
        $this->insert_log( $class, $message );
    }

    public function i( $message ) {
        $this->insert_log( $this::INFO, $message );
    }

    public function d( $message ) {
        $this->insert_log( $this::DEBUG, $message );
    }

    public function e( $message ) {
        $this->insert_log( $this::ERROR, $message );
    }

    public function w( $message ) {
        $this->insert_log( $this::WARN, $message );
    }

}