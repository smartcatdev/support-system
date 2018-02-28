<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


/**
 * Simple class for containing menu page definitions.
 *
 * @since 1.6.0
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
     * @since 1.6.0
     */
    public function __construct() {
        $this->assets_url = trailingslashit( resolve_url(  "assets/admin/menu-pages/$this->slug" ) );
    }

    /**
     * Return the URL of an asset in the menu page folder.
     *
     * @param string $path
     *
     * @since 1.6.0
     * @return string
     */
    public function asset_url( $path = '' ) {
        return $this->assets_url . ltrim( $path, '/' );
    }

    /**
     * Return the path of an asset in the menu page folder.
     *
     * @param string $path
     *
     * @since 1.6.0
     * @return string
     */
    public function asset_path( $path = '' ) {
        return $this->assets_path . ltrim( $path, '/' );
    }

    /**
     * Add the menu page and initialize the load action.
     *
     * @since 1.6.0
     * @return void
     */
    public function add() {
        add_action( 'load-' . $this->add_menu_page(), array( $this, 'on_load' ) );
    }

    /**
     * Handle the menu page load event.
     *
     * @since 1.6.0
     * @return void
     */
    public function on_load() {
        $this->screen = get_current_screen();
    }

    /**
     * Make a call to add_menu_page()
     *
     * @since 1.6.0
     * @return mixed
     */
    abstract public function add_menu_page();

}
