<?php
/**
 *
 * @since 1.6.1
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


/**
 * Simple class for containing menu page definitions.
 *
 * @since 1.6.1
 * @package ucare
 */
abstract class MenuPage {

    /**
     * Add the menu page and initialize the load action.
     *
     * @since 1.6.1
     * @return void
     */
    public function add() {
        add_action( 'load-' . $this->add_menu_page(), array( $this, 'on_load' ) );
    }

    /**
     * Handle the menu page load event.
     *
     * @since 1.6.1
     * @return void
     */
    public function on_load() {
        // Do page load
    }

    /**
     * Make a call to add_menu_page()
     *
     * @since 1.6.1
     * @return mixed
     */
    abstract public function add_menu_page();

}
