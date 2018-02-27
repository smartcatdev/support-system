<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


/***
 * Tutorial page that displays when the plugin updates.
 *
 * @since 1.6.0
 * @package ucare
 */
class TutorialPage extends MenuPage {

    /**
     * Make a call to add_menu_page()
     *
     * @since 1.6.0
     * @return mixed
     */
    public function add_menu_page() {
        return add_submenu_page( null, __( 'uCare - Introduction', 'ucare' ), __( 'uCare - Introduction', 'ucare' ), 'manage_options', 'ucare-tutorial', array( $this, 'render' ) );
    }

    /**
     * Output the tutorial page
     *
     * @since 1.6.0
     * @return void
     */
    public function render() {
        get_template( 'admin/menu-page-tutorial' );
    }

}
