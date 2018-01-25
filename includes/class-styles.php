<?php
/**
 *
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
class Styles extends \WP_Styles {

    /**
     * Constructor.
     *
     * @since 1.6.0
     */
    public function __construct() {
        parent::__construct();

        /**
         * Fires when the Scripts instance is initialized.
         *
         * @since 1.6.0
         *
         * @param Scripts $this Scripts instance (passed by reference).
         */
        do_action_ref_array( 'ucare_default_styles', array(&$this) );
    }

}
