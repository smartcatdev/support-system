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
     * The current screen resource
     *
     * @var \WP_Screen
     */
    protected $screen = '';

    /**
     * The menu page slug (un-prefixed)
     *
     * @var string
     */
    protected $slug = '';

    /**
     * The URL for the menu page asset directory.
     *
     * @var string
     */
    protected $assets_url = '';

    /**
     * Constructor.
     *
     * @since 1.6.1
     */
    public function __construct() {
        $this->assets_url = trailingslashit( resolve_url( "assets/admin/menu-pages/$this->slug" ) );
    }

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
        $this->screen = get_current_screen();
    }

    /**
     * Make a call to add_menu_page()
     *
     * @since 1.6.1
     * @return mixed
     */
    abstract public function add_menu_page();

}
