<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;

/**
 * Class for logging messages.
 *
 * @since 1.6.0
 * @package ucare
 */
class Logger {

    /**
     * Informational class
     */
    const INFO = 'i';

    /**
     * Error class
     */
    const ERROR = 'e';

    /**
     * Debug class
     */
    const DEBUG = 'd';

    /**
     * Warning class
     */
    const WARN = 'w';


    /**
     * The type of log message this logger has been configured for.
     *
     * @var string
     */
    public $type;


    /**
     * @param string $tag
     *
     * @since 1.6.0
     */
    public function __construct( $tag = 'general' ) {
        $this->type = $tag;
    }


    /**
     * Create a new log message entry in the logs table.
     *
     * @global $wpdb
     *
     * @param string $class
     * @param string $message
     *
     * @since 1.6.0
     * @return void
     */
    protected function insert_log( $class, $message ) {

        if ( get_option( Options::LOGGING_ENABLED ) ) {
            global $wpdb;

            $q = "INSERT INTO {$wpdb->prefix}ucare_logs VALUES( NULL, %s, %s, %s, %s )";
            $wpdb->query( $wpdb->prepare( $q, array( $class, $this->type, current_time( 'mysql', 1 ), $message ) ) );
        }

    }


    /**
     * Log a message.
     *
     * @param string $class
     * @param string $message
     *
     * @since 1.0.0
     * @return void
     */
    public function log( $class, $message ) {
        $this->insert_log( $class, $message );
    }


    /**
     * Convenience method to log with the informational class.
     *
     * @param string $message
     *
     * @since 1.6.0
     * @return void
     */
    public function i( $message ) {
        $this->insert_log( $this::INFO, $message );
    }


    /**
     * Convenience method to log with the debug class.
     *
     * @param string $message
     *
     * @since 1.6.0
     * @return void
     */
    public function d( $message ) {
        $this->insert_log( $this::DEBUG, $message );
    }


    /**
     * Convenience method to log with the error class.
     *
     * @param string $message
     *
     * @since 1.6.0
     * @return void
     */
    public function e( $message ) {
        $this->insert_log( $this::ERROR, $message );
    }


    /**
     * Convenience method to log with the warning class.
     *
     * @param string $message
     *
     * @since 1.6.0
     * @return void
     */
    public function w( $message ) {
        $this->insert_log( $this::WARN, $message );
    }

}
