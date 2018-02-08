<?php
/**
 *
 * @since 1.6.1
 * @package ucare
 */
namespace ucare;


/**
 * Add-ons menu page.
 *
 * @since 1.6.1
 * @package ucare
 */
class AddonsPage extends MenuPage {

    /**
     * Make a call to add_menu_page()
     *
     * @since 1.6.1
     * @return string
     */
    public function add_menu_page() {
        return add_submenu_page( 'ucare_support', __( 'Add-ons', 'ucare' ), __( 'Add-ons', 'ucare' ), 'manage_options', 'ucare-add-ons', array( $this, 'render' ) );
    }

    /**
     *
     */
    public function on_load() {

    }

    /**
     * Output the menu page.
     *
     * @since 1.6.1
     */
    public function render() {
        echo '<h1>', __( 'Add-ons', 'ucare' ), '</h1><div id="ucare-add-ons"></div>';
    }

}
