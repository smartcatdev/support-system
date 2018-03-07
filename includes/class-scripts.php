<?php
/**
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


/**
 * Placeholder class to recognize support system enqueues.
 *
 * @since 1.6.0
 * @package ucare
 */
class Scripts extends \WP_Scripts {

    /**
     * Initialize the class.
     *
     * @since 3.4.0
     */
    public function __construct() {
        parent::__construct();

        /**
         * Fires when the Scripts instance is initialized.
         *
         * @since 2.6.0
         *
         * @param Scripts $this Scripts instance (passed by reference).
         */
        do_action_ref_array( 'ucare_default_scripts', array(&$this) );
    }

    /**
     * Apply additional cache-busting in dev mode.
     *
     * @param string $handle
     * @param string $src
     * @param array  $deps
     * @param bool   $ver
     * @param null   $args
     *
     * @since 1.6.0
     * @return bool
     */
    public function add( $handle, $src, $deps = array(), $ver = false, $args = null ) {
        return parent::add( $handle, ucare_cache_bust_url( $src ), $deps, $ver, $args );
    }

}
