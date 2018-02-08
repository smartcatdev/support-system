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
     * Base slug
     *
     * @var string
     */
    protected $slug = 'add-ons';

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
     * Do enqueues and setup data for the page.
     *
     * @since 1.6.1
     * @return void
     */
    public function on_load() {
        parent::on_load();

        $this->enqueue_scripts();
    }

    /**
     * Enqueue menu page scripts.
     *
     * @since 1.6.1
     * @return void
     */
    private function enqueue_scripts() {
        $bundle = ucare_dev_var( 'bundle.production.min.js', 'bundle.js' );

        wp_enqueue_script( 'ucare-add-ons', strcat( $this->assets_url, 'build/', $bundle ), array( 'react' ), PLUGIN_VERSION );
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
