<?php
/**
 *
 * @package ucare
 * @since 1.4.2
 */
namespace ucare;


/**
 * Trait for creating singleton objects where only one instance is required throughout the life of the request.
 *
 * @package ucare
 * @since 1.4.2
 */
trait Singleton {

    /**
     * @var Singleton $instance The instance.
     * @access private
     * @since 1.4.2
     */
    private static $instance = null;


    /**
     * Prevent using the new operator.
     *
     * @since 1.4.2
     * @access private
     */
    private function __construct() {}


    /**
     * Construct and initialize the object instance.
     *
     * @since 1.4.2
     * @access private
     * @return Singleton
     */
    public static function instance() {

        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->initialize();
        }

        return self::$instance;

    }

    /**
     * Initialize the object instance.
     *
     * @return void
     * @since 1.4.2
     * @access protected
     */
    abstract protected function initialize();

}
