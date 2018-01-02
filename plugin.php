<?php
/**
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
<<<<<<< HEAD
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress.
 * Version: 1.4.2
=======
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress. 
 * Version: 1.5.0
>>>>>>> origin/master
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

    // Add custom action links to the plugins table row
    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucare\add_plugin_action_links' );


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

            // All done
            do_action( 'ucare_loaded', $this );

        }


        /**
         * Define plugin constants.
         *
         * @since 14.2
         * @access private
         * @return void
         */
        private function do_defines() {

            define( 'UCARE_DIR', plugin_dir_path( __FILE__ ) );
            define( 'UCARE_URL', plugin_dir_url(  __FILE__ ) );

            define( 'UCARE_TEMPLATES_PATH', UCARE_DIR . 'templates/' );
            define( 'UCARE_PARTIALS_PATH',  UCARE_DIR . 'templates/partials/' );
            define( 'UCARE_INCLUDES_PATH',  UCARE_DIR . 'includes/'  );


            // Define which e-commerce mode the plugin is running in
            if ( get_option( Options::ECOMMERCE ) ) {

                // Use WooCommerce specific settings
                if ( class_exists( 'WooCommerce' ) ) {
                    define( 'UCARE_ECOMMERCE_MODE', 'woo' );

                // Use EDD specific settings
                } else if ( class_exists( 'Easy_Digital_Downloads' ) ) {
                    define( 'UCARE_ECOMMERCE_MODE', 'edd' );

                // Use basic e-commerce support
                } else {
                    define( 'UCARE_ECOMMERCE_MODE', 'default' );
                }

            }

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


            include_once dirname( __FILE__ ) . '/includes/library/extension-licensing.php';
            include_once dirname( __FILE__ ) . '/includes/library/class-bootstrap-nav-walker.php';


            include_once dirname( __FILE__ ) . '/includes/email-notifications.php';
            include_once dirname( __FILE__ ) . '/includes/cron.php';



            include_once dirname( __FILE__ ) . '/includes/class-field.php';

            include_once dirname( __FILE__ ) . '/includes/functions.php';
            include_once dirname( __FILE__ ) . '/includes/functions-hooks.php';
            include_once dirname( __FILE__ ) . '/includes/functions-formatting.php';
            include_once dirname( __FILE__ ) . '/includes/functions-fonts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-comment.php';
            include_once dirname( __FILE__ ) . '/includes/functions-user.php';
            include_once dirname( __FILE__ ) . '/includes/functions-template.php';
            include_once dirname( __FILE__ ) . '/includes/functions-sanitize.php';
            include_once dirname( __FILE__ ) . '/includes/functions-scripts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-styles.php';
            include_once dirname( __FILE__ ) . '/includes/functions-helpers.php';
            include_once dirname( __FILE__ ) . '/includes/functions-sidebar.php';
            include_once dirname( __FILE__ ) . '/includes/functions-media.php';
            include_once dirname( __FILE__ ) . '/includes/functions-settings.php';
            include_once dirname( __FILE__ ) . '/includes/functions-rest-api.php';
            include_once dirname( __FILE__ ) . '/includes/functions-widgets.php';
            include_once dirname( __FILE__ ) . '/includes/functions-field.php';
            include_once dirname( __FILE__ ) . '/includes/functions-public.php';
            include_once dirname( __FILE__ ) . '/includes/functions-shortcodes.php';
            include_once dirname( __FILE__ ) . '/includes/functions-wp-scripts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-post-support_ticket.php';
            include_once dirname( __FILE__ ) . '/includes/functions-taxonomy-ticket_category.php';
            include_once dirname( __FILE__ ) . '/includes/functions-deprecated.php';
            include_once dirname( __FILE__ ) . '/includes/functions-deprecated-public.php';


            // If eCommerce support is enabled pull in general support functions
            if ( defined( 'UCARE_ECOMMERCE_MODE' ) ) {
                include_once dirname( __FILE__ ) . '/includes/functions-ecommerce.php';

                // Pull in function definitions for EDD
                if ( UCARE_ECOMMERCE_MODE == 'edd' ) {
                    include_once dirname( __FILE__ ) . '/includes/functions-ecommerce-edd.php';

                // Pull in function definitions for WooCommerce
                } else if ( UCARE_ECOMMERCE_MODE == 'woo' ) {
                    include_once dirname( __FILE__ ) . '/includes/functions-ecommerce-woo.php';
                }

            }


            // Pull in functions used in the WordPress admin
            if ( is_admin() ) {
                include_once dirname( __FILE__ ) . '/includes/admin/functions-menu.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-settings.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-admin-bar.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-metabox.php';
            }

        }


        private function init_licensing() {

            $page = array(
                'parent_slug' => 'ucare_support',
                'page_title'  => __( 'uCare Licenses', 'ucare' ),
                'menu_title'  => __( 'Licenses', 'ucare' ),
                'capability'  => 'manage_options',
                'menu_slug'   => 'ucare-licenses'
            );

            $this->set( 'license_manager', new \SC_License_Manager( 'ucare', 'submenu', $page ) );

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

        if ( !get_option( Options::DEV_MODE ) ) {
            $links['deactivate'] = sprintf( '<span id="feedback-prompt">%s</span>', $links['deactivate'] );
        }

        $custom = array(
            'settings' => sprintf( '<a href="%1$s">%2$s</a>', menu_page_url( 'uc-settings', false ), __( 'Settings', 'ucare' ) )
        );

        return array_merge( $links, $custom );

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
 * Get a path relative to the root of the plugin directory.
 *
 * @since 1.4.2
 * @param string $path
 * @return string
 */
function resolve_path( $path = '' ) {
    return plugin_dir_path( __FILE__ ) . $path;
}


/**
 * Get a URL relative to the root of the plugin directory.
 *
 * @param string $path
 * @since 1.4.2
 * @return string
 */
function resolve_url( $path = '' ) {
    return plugin_dir_url( __FILE__ ) . $path;
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
