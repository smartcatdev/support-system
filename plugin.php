<?php
/*
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress. 
 * Version: 1.4.1
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * license: GPL V2
 * 
 */

namespace ucare;

// Die if access directly
if( !defined( 'ABSPATH' ) ) {
    die();
}

include_once dirname( __FILE__ ) . '/constants.php';


if( PHP_VERSION >= MIN_PHP_VERSION ) {


    /**
     * Main plugin class
     *
     * @package ucare
     * @since 1.4.2
     */
    class uCare {

        private static $instance = null;


        public static function instance() {

            if ( is_null( self::$instance ) ) {
                self::$instance = self::initialize();
            }

            return self::$instance;

        }

        private static function initialize() {

            self::do_defines();
            self::do_includes();

            self::add_hooks();

            return new self();
        }


        private static function do_defines() {

            define( 'UCARE_DIR', plugin_dir_path( __FILE__ ) );
            define( 'UCARE_URL', plugin_dir_url(  __FILE__ ) );

            define( 'UCARE_TEMPLATES_PATH', UCARE_DIR . 'templates/' );
            define( 'UCARE_PARTIALS_PATH',  UCARE_DIR . 'templates/partials/' );
            define( 'UCARE_INCLUDES_PATH',  UCARE_DIR . 'includes/'  );

        }


        private static function do_includes() {

            include_once dirname( __FILE__ ) . '/lib/mail/mail.php';
            include_once dirname( __FILE__ ) . '/includes/functions.php';
            include_once dirname( __FILE__ ) . '/includes/functions-public.php';
            include_once dirname( __FILE__ ) . '/includes/comment.php';
            include_once dirname( __FILE__ ) . '/includes/email-notifications.php';
            include_once dirname( __FILE__ ) . '/includes/cron.php';
            include_once dirname( __FILE__ ) . '/includes/extension-licensing.php';
            include_once dirname( __FILE__ ) . '/includes/post-support_ticket.php';
            include_once dirname( __FILE__ ) . '/includes/admin-menu.php';
            include_once dirname( __FILE__ ) . '/includes/widgets.php';


            /**
             * @since 1.4.2
             */
            include_once dirname( __FILE__ ) . '/includes/class-field.php';
            include_once dirname( __FILE__ ) . '/includes/class-bootstrap-nav-walker.php';
            include_once dirname( __FILE__ ) . '/includes/template.php';
            include_once dirname( __FILE__ ) . '/includes/sanitize.php';
            include_once dirname( __FILE__ ) . '/includes/helpers.php';
            include_once dirname( __FILE__ ) . '/includes/user.php';
            include_once dirname( __FILE__ ) . '/includes/metabox.php';
            include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
            include_once dirname( __FILE__ ) . '/includes/taxonomy-ticket_category.php';

            if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
                include_once dirname( __FILE__ ) . '/lib/license/EDD_SL_Plugin_Updater.php';
            }

        }


        private static function add_hooks() {

        }

    }



    function ucare() {
        return uCare::instance();
    }



    function enqueue_scripts() {
        wp_enqueue_style( 'ucare-login-form', plugin_url( 'assets/css/login.css' ), null, PLUGIN_VERSION );
    }

    add_action( 'wp_enqueue_scripts', 'ucare\enqueue_scripts' );



    function load_text_domain() {
        load_plugin_textdomain( 'ucare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    add_action( 'plugins_loaded', 'ucare\load_text_domain' );



    function add_plugin_action_links( $links ) {

        if ( !get_option( Options::DEV_MODE ) ) {
            $links['deactivate'] = sprintf( '<span id="feedback-prompt">%s</span>', $links['deactivate'] );
        }

        $custom = array(
            'settings' => sprintf( '<a href="%1$s">%2$s</a>', menu_page_url( 'uc-settings', false ), __( 'Settings', 'ucare' ) )
        );

        return array_merge( $links, $custom );

    }

    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucare\add_plugin_action_links' );



    // TODO move this to a plugins_loaded callback
    ucare();

    //<editor-fold desc="Legacy Boot">
    do_action_deprecated( 'support_register_autoloader', include_once 'vendor/autoload.php', '1.4.2' );
    Plugin::boot( PLUGIN_ID, PLUGIN_VERSION, __FILE__ );
    //</editor-fold>


} else {

    add_action( 'admin_notices', function () { ?>

        <div class="notice notice-error is-dismissible">
            <p>
                <?php _e( 'Your PHP version ' . PHP_VERSION . ' does not meet minimum' .
                          'requirements. uCare Support requires version 5.5 or higher', 'ucare' ); ?>
            </p>
        </div>

    <?php } );

}


/**
 * @since 1.4.2
 * @param string $path
 * @return string
 */
function resolve_path( $path = '' ) {
    return plugin_dir_path( __FILE__ ) . $path;
}


/**
 * @param string $path
 * @since 1.4.2
 * @return string
 */
function resolve_url( $path = '' ) {
    return plugin_dir_url( __FILE__ ) . $path;
}
