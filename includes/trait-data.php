<?php
/**
 *
 * @package ucare
 * @since 1.4.2
 */
namespace ucare;


/**
 * Add an object cache to an object.
 *
 * @package ucare
 * @since 1.4.2
 */
trait Data {

    /**
     * @var array $data The object cache.
     * @access private
     * @since 1.4.2
     */
    private $data = array();


    /**
     * Get a value from the cache.
     *
     * @param string $var     The name of the variable to get.
     * @param string $default The default to return if not found.
     *
     * @return mixed
     * @since 1.4.2
     * @access public
     */
    public function get( $var, $default = '' ) {

        if ( isset( $this->data[ $var ] ) ) {
            return $this->data[ $var ];
        }

        return $default;

    }

    /**
     * Set a value in the cache.
     *
     * @param string $key   The name of the variable to set.
     * @param mixed  $value The value of the variable.
     * @access public
     */
    public function set( $key, $value ) {
        $this->data[ $key ] = $value;
    }

}
