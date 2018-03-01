<?php
/**
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress.
 * Version: 1.6.0
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * license: GPL V2
 *
 *
 * @package ucare
 * @since 1.0.0
 */
namespace ucare;


// Die if access directly
if ( !defined( 'ABSPATH' ) ) {
    die();
}

// Pull in constant declarations
include_once dirname( __FILE__ ) . '/constants.php';


// PHP Version check
if ( PHP_VERSION >= MIN_PHP_VERSION ) {

    // Pull in immediate dependencies
    include_once dirname( __FILE__ ) . '/includes/trait-data.php';
    include_once dirname( __FILE__ ) . '/includes/trait-singleton.php';

    // Boot plugin
    add_action( 'plugins_loaded', 'ucare\ucare' );

    // load the plugin text domain
    add_action( 'plugins_loaded', 'ucare\load_text_domain' );

    // Call init on support pages
    add_action( 'wp', 'ucare\init' );

    // Add custom action links to the plugins table row
    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucare\add_plugin_action_links' );

    register_activation_hook( __FILE__, 'ucare\activate' );
    register_deactivation_hook( __FILE__, 'ucare\deactivate' );


    /**
     * Main plugin class
     *
     * @package ucare
     * @since 1.4.2
     */
    final class uCare {

        use Data;
        use Singleton;

        /**
         * Sets up includes and defines global constants.
         *
         * @since 1.4.2
         * @access protected
         * @return void
         */
        protected function initialize() {
            $this->do_defines();
            $this->do_includes();

            $this->init_licensing();
            $this->init_marketing();

            // All done
            do_action( 'ucare_loaded', $this );
        }

        /**
         * Define plugin constants.
         *
         * @global $ucare_db_version
         *
         * @since 1.4.2
         * @access private
         * @return void
         */
        private function do_defines() {
            define( 'UCARE_DIR', plugin_dir_path( __FILE__ ) );
            define( 'UCARE_URL', plugin_dir_url(  __FILE__ ) );

            define( 'UCARE_TEMPLATES_PATH', UCARE_DIR . 'templates/' );
            define( 'UCARE_PARTIALS_PATH',  UCARE_DIR . 'templates/partials/' );
            define( 'UCARE_INCLUDES_PATH',  UCARE_DIR . 'includes/'  );

            if ( defined( 'UCARE_DEV_MODE' ) && UCARE_DEV_MODE ) {
                define( 'UCARE_SERVER_ADDRESS', 'http://ucaresupport.staging.wpengine.com' );
            } else {
                define( 'UCARE_SERVER_ADDRESS', 'https://ucaresupport.com' );
            }

            register_early_settings();
            $this->define_ecommerce_mode();

            global $ucare_db_version;
            $ucare_db_version = get_option( Options::DATABASE_VERSION );
        }

        /**
         * Include plugin files.
         *
         * @since 1.4.2
         * @access private
         * @return void
         */
        private function do_includes() {
            include_once dirname( __FILE__ ) . '/lib/mail/mail.php';

            include_once dirname( __FILE__ ) . '/includes/library/class-edd-sl-plugin-updater.php';
            include_once dirname( __FILE__ ) . '/includes/library/class-bootstrap-nav-walker.php';
            include_once dirname( __FILE__ ) . '/includes/library/sc-plugin-marketing.php';


            include_once dirname( __FILE__ ) . '/includes/email-notifications.php';
            include_once dirname( __FILE__ ) . '/includes/cron.php';



            include_once dirname( __FILE__ ) . '/includes/class-field.php';
            include_once dirname( __FILE__ ) . '/includes/class-logger.php';
            include_once dirname( __FILE__ ) . '/includes/class-toolbar.php';
            include_once dirname( __FILE__ ) . '/includes/class-scripts.php';
            include_once dirname( __FILE__ ) . '/includes/class-styles.php';
            include_once dirname( __FILE__ ) . '/includes/class-license-manager.php';

            include_once dirname( __FILE__ ) . '/includes/functions.php';
            include_once dirname( __FILE__ ) . '/includes/functions-request.php';
            include_once dirname( __FILE__ ) . '/includes/functions-database.php';
            include_once dirname( __FILE__ ) . '/includes/functions-auth.php';
            include_once dirname( __FILE__ ) . '/includes/functions-hooks.php';
            include_once dirname( __FILE__ ) . '/includes/functions-formatting.php';
            include_once dirname( __FILE__ ) . '/includes/functions-application.php';
            include_once dirname( __FILE__ ) . '/includes/functions-fonts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-comment.php';
            include_once dirname( __FILE__ ) . '/includes/functions-user.php';
            include_once dirname( __FILE__ ) . '/includes/functions-template.php';
            include_once dirname( __FILE__ ) . '/includes/functions-template-general.php';
            include_once dirname( __FILE__ ) . '/includes/functions-sanitize.php';
            include_once dirname( __FILE__ ) . '/includes/functions-scripts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-styles.php';
            include_once dirname( __FILE__ ) . '/includes/functions-helpers.php';
            include_once dirname( __FILE__ ) . '/includes/functions-sidebar.php';
            include_once dirname( __FILE__ ) . '/includes/functions-media.php';
            include_once dirname( __FILE__ ) . '/includes/functions-settings.php';
            include_once dirname( __FILE__ ) . '/includes/functions-rest-api.php';
            include_once dirname( __FILE__ ) . '/includes/functions-rest-endpoints.php';
            include_once dirname( __FILE__ ) . '/includes/functions-widgets.php';
            include_once dirname( __FILE__ ) . '/includes/functions-field.php';
            include_once dirname( __FILE__ ) . '/includes/functions-public.php';
            include_once dirname( __FILE__ ) . '/includes/functions-shortcodes.php';
            include_once dirname( __FILE__ ) . '/includes/functions-scripts-wp.php';
            include_once dirname( __FILE__ ) . '/includes/functions-post-email_template.php';
            include_once dirname( __FILE__ ) . '/includes/functions-post-support_ticket.php';
            include_once dirname( __FILE__ ) . '/includes/functions-taxonomy-ticket_category.php';
            include_once dirname( __FILE__ ) . '/includes/functions-deprecated.php';
            include_once dirname( __FILE__ ) . '/includes/functions-deprecated-public.php';
            include_once dirname( __FILE__ ) . '/includes/functions-toolbar.php';
            include_once dirname( __FILE__ ) . '/includes/functions-licensing.php';
            include_once dirname( __FILE__ ) . '/includes/functions-admin-bar.php';
            include_once dirname( __FILE__ ) . '/includes/functions-ecommerce.php';

            // Pull in functions used in the WordPress admin
            if ( is_admin() ) {
                include_once dirname( __FILE__ ) . '/includes/admin/functions.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-menu.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-user.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-scripts.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-settings.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-upgrade.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-post-support_ticket.php';

                include_once dirname( __FILE__ ) . '/includes/admin/class-menu-page.php';
                include_once dirname( __FILE__ ) . '/includes/admin/menu-pages/class-addons-page.php';
                include_once dirname( __FILE__ ) . '/includes/admin/menu-pages/class-tutorial-page.php';
            }
        }

        /**
         * Initialize module for handling extension licensing.
         *
         * @since 1.4.2
         * @access private
         * @return void
         */
        private function init_licensing() {
            $this->set( 'license_manager', ucare_get_license_manager() );
        }

        /**
         * Initialize plugin marketing module.
         *
         * @since 1.6.0
         * @return void
         */
        private function init_marketing() {
            $args = array(
                'url' => UCARE_SERVER_ADDRESS
            );
            sc_plugin_marketing( $args );
        }

        /**
         * Setup the define for the E-Commerce mode that the plugin is running in.
         *
         * @since 1.6.0
         * @return void
         */
        private function define_ecommerce_mode() {
            $enabled = get_option( Options::ECOMMERCE );
            if ( empty( $enabled ) ) {
                return;
            }
            if ( class_exists( 'WooCommerce' ) ){
                define( 'UCARE_ECOMMERCE_MODE', 'woo' );
            } else if ( class_exists( 'Easy_Digital_Downloads' ) ) {
                define( 'UCARE_ECOMMERCE_MODE', 'edd' );
            } else {
                define( 'UCARE_ECOMMERCE_MODE', 'default' );
            }
        }

    }


    /**
     * Runs when a support page has been loaded.
     *
     * @action wp
     *
     * @since 1.6.0
     * @return void
     */
    function init() {
        /**
         * Notify as soon as we know a support page has loaded.
         *
         * @since 1.6.0
         */
        if ( is_a_support_page() ) do_action( 'ucare_init' );
    }


    /**
     * Register settings that are required early in the bootstrapping process.
     *
     * @since 1.6.0
     * @return void
     */
    function register_early_settings() {
        register_setting( 'uc-general', Options::ECOMMERCE, array(
            'type'    => 'string',
            'default' => Defaults::ECOMMERCE,
            'sanitize_callback' => 'ucare\sanitize_boolean'
        ) );
    }


    /**
     * Handle plugin activation.
     *
     * @since 1.6.0
     * @return void
     */
    function activate() {

    }


    /**
     * Handle plugin deactivation.
     *
     * @since 1.6.0
     * @return void
     */
    function deactivate() {
        ucare();

        // Delete the first run option on de-activate This triggers the First Run welcome screen to load on reload
        delete_option( Options::FIRST_RUN );

        // Cleanup custom user roles and caps
        remove_role_capabilities();
        remove_user_roles();

        // Unregister custom post type data
        unregister_post_type( 'support_ticket' );
        unregister_post_type( 'email_template' );
        unregister_taxonomy( 'ticket_category' );

        // Cleanup license manager data
        ucare_get_license_manager()->cleanup();

        // Execute uninstall script if we are in dev mode.
        if ( ucare_in_dev_mode() ) {
            include_once dirname( __FILE__ ) . '/uninstall.php';
        }
    }


    /**
     * Get the main plugin instance.
     *
     * @action plugins_loaded
     *
     * @since 1.4.2
     * @return Singleton|uCare
     */
    function ucare() {
        return uCare::instance();
    }


    /**
     * Action to load the plugin text domain.
     *
     * @action plugins_loaded
     *
     * @since 1.0.0
     * @return void
     */
    function load_text_domain() {
        load_plugin_textdomain( 'ucare', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
    }


    /**
     * Action to add custom links to the plugins table row.
     *
     * @action plugin_action_links_{$basename}
     *
     * @param $links
     *
     * @since 1.0.0
     * @return array
     */
    function add_plugin_action_links( $links ) {
        $custom = array(
            'settings' => sprintf(
                '<a href="%1$s">%2$s</a>', menu_page_url( 'uc-settings', false ), __( 'Settings', 'ucare' ) )
        );

        if ( ucare_get_license_manager()->has_licenses() ) {
            $custom['licenses'] = sprintf(
                '<a href="%1$s">%2$s</a>', menu_page_url( 'ucare-add-ons', false ), __( 'Add-ons', 'ucare' )
            );
        }

        if ( !ucare_in_dev_mode() ) {
            $links['deactivate'] = sprintf( '<span id="feedback-prompt">%s</span>', $links['deactivate'] );
        }

        return array_merge( $custom, $links );
    }

    //<editor-fold desc="Legacy Boot">
        include_once dirname( __FILE__ ) . '/includes/functions.php';
        include_once dirname( __FILE__ ) . '/includes/functions-public.php';
        include_once dirname( __FILE__ ) . '/includes/functions-deprecated.php';
        include_once dirname( __FILE__ ) . '/includes/functions-deprecated-public.php';

        do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );
        Plugin::boot( PLUGIN_ID, PLUGIN_VERSION, __FILE__ );
    //</editor-fold>


} else {
    /**
     * Add an error in the admin dashboard if the server's PHP version does not meet the minimum requirements.
     *
     * @since 1.0.0
     */
    admin_notification( sprintf( __( 'Your PHP version %s does not meet minimum requirements. uCare Support requires version %s or higher', 'ucare' ), PHP_VERSION, MIN_PHP_VERSION ) );
}


/**
 * Add a message to the admin notification area.
 *
 * @param string $message
 * @param string $type
 * @param bool   $dismissible
 *
 * @since 1.4.2
 * @return void
 */
function admin_notification( $message, $type = 'error', $dismissible = true ) {
    add_action( 'admin_notices', function () use ( $message, $type, $dismissible ) {
        printf( '<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>', esc_attr( $type ), $dismissible ? 'is-dismissible' : '', $message );
    } );
}


/**
 * Get a path relative to the root of the plugin directory.
 *
 * @since 1.4.2
 * @param string $path
 * @return string
 */
function resolve_path( $path = '' ) {
    return plugin_dir_path( __FILE__ ) . join( DIRECTORY_SEPARATOR, func_get_args() );
}


/**
 * Get a URL relative to the root of the plugin directory.
 *
 * @param string $path
 *
 * @since 1.4.2
 * @return string
 */
function resolve_url( $path = '' ) {
    return plugin_dir_url( __FILE__ ) . join( DIRECTORY_SEPARATOR, func_get_args() );
}


/**
 * Build a namespace qualifier
 *
 * @param string $qualifier
 *
 * @since 1.6.0
 * @return string
 */
function fqn( $qualifier = '' ) {
    return sprintf( '\\%1$s\\%2$s', __NAMESPACE__, join( '\\', func_get_args() ) );
}
