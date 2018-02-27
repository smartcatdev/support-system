<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


/**
 * Add-ons menu page.
 *
 * @since 1.6.0
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
     * Constructor.
     *
     * @since 1.6.0
     */
    public function __construct() {
        parent::__construct();
        add_action( 'admin_head', array( $this, 'init' ) );
    }

    /**
     * Make a call to add_menu_page()
     *
     * @since 1.6.0
     * @return string
     */
    public function add_menu_page() {
        return add_submenu_page( 'ucare_support', __( 'Add-ons', 'ucare' ), __( 'Add-ons', 'ucare' ), 'manage_options', 'ucare-add-ons', array( $this, 'render' ) );
    }

    /**
     * Do enqueues and setup data for the page.
     *
     * @since 1.6.0
     * @return void
     */
    public function on_load() {
        parent::on_load();

        $this->enqueue_scripts();
    }

    /**
     * Display notification if there are any license problems in the WordPress admin
     *
     * @since 1.6.0
     * @return void
     */
    public function init() {
        if ( $this->screen ) {
            return;
        }

        $licenses = get_licensing_data();
        $problems = array();

        foreach ( $licenses as $license ) {
            if ( $license['status'] !== 'valid' ) {
                $problems[] = $license;
            }
        }

        if ( count( $problems ) > 0 ) {
            admin_notification(
                sprintf(
                    '<span class="dashicons dashicons-warning"></span> %1$s <a href="%2$s">%3$s</a>',
                    __( 'One of your uCare add-ons requires attention.', 'ucare' ), menu_page_url( 'ucare-add-ons', false ), __( 'Manage add-ons', 'ucare' )
                ),
                'warning'
            );
        }
    }

    /**
     * Enqueue menu page scripts.
     *
     * @since 1.6.0
     * @return void
     */
    private function enqueue_scripts() {
        $bundle  = ucare_dev_var( 'bundle.production.min.js', 'bundle.dev.js' );

        $deps = array(
            'react',
            'redux',
            'react-redux',
            'react-dom'
        );

        $localize = array(
            'vars' => array(
                'products'   => $this->fetch_products(),
                'licenses'   => get_licensing_data(),
                'rest_url'   => rest_url( 'ucare/v1/extensions/licenses' ),
                'wp_nonce'   => wp_create_nonce( 'wp_rest' )
            ),
            'strings' => array(
                'active'      => __( 'Active', 'ucare' ),
                'installed'   => __( 'Installed', 'ucare' ),
                'license'     => __( 'License Key', 'ucare' ),
                'expiration'  => __( 'Expiration', 'ucare' ),
                'status'      => __( 'Status', 'ucare' ),
                'renew'       => __( 'Renew', 'ucare' ),
                'activate'    => __( 'Activate', 'ucare' ),
                'deactivate'  => __( 'Deactivate', 'ucare' ),
                'get_add_on'  => __( 'Get Add-on', 'ucare' ),
                'page_title'  => __( 'uCare Add-ons', 'ucare' ),
                'coming_soon' => __( 'Coming Soon', 'ucare' )
            )
        );

        wp_register_script( 'ucare-add-ons', strcat( $this->assets_url, 'build/', $bundle ), $deps, PLUGIN_VERSION, true );
        wp_localize_script( 'ucare-add-ons', 'ucare_addons_l10n', $localize );

        wp_enqueue_script( 'ucare-add-ons' );
        wp_enqueue_style( 'ucare-add-ons', strcat( $this->assets_url, 'build/style.css' ), null, PLUGIN_VERSION );
    }

    /**
     * Output the menu page.
     *
     * @since 1.6.0
     */
    public function render() { ?>
        <div class="wrap ucare-admin-page">
            <div id="ucare-settings-header">
                <div class="inner">
                    <div class="ucare-logo">
                        <img src="<?php esc_url_e( resolve_url( 'assets/images/admin-icon-grey.png' ) ); ?>" />
                    </div>
                    <div class="page-title">
                        <span class="title-text"><?php _e( 'uCare Add-ons', 'ucare' ); ?></span>
                        <span class="small version-number">v<?php esc_html_e( PLUGIN_VERSION ); ?></span>
                    </div>
                </div>
            </div>
            <h2 style="display: none"></h2>
            <div id="ucare-add-ons"></div>
        </div>
    <?php }

    /**
     * Fetch products from the cache. If cache has expired, re-cache from our server.
     *
     * @since 1.6.0
     * @return array
     */
    private function fetch_products() {
        $cached = get_transient( 'ucare_addons_cache' );

        if ( is_array( $cached ) ) {
            return $cached;
        }

        $response = wp_remote_get( 'http://ucaresupport.staging.wpengine.com/wp-json/smartcat/v1/downloads' );

        if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return array();
        }

        $products = json_decode( wp_remote_retrieve_body( $response ), true );
        set_transient( 'ucare_addons_cache', $products, 60 * 60 * 24 );

        return $products;
    }

}
