<?php
/**
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress. 
 * Version: 1.4.2
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
if( !defined( 'ABSPATH' ) ) {
    die();
}

// Pull in constant declarations
include_once dirname( __FILE__ ) . '/constants.php';


// PHP Version check
if( PHP_VERSION >= MIN_PHP_VERSION ) {


    // Pull in immediate dependencies
    include_once dirname( __FILE__ ) . '/includes/trait-data.php';
    include_once dirname( __FILE__ ) . '/includes/trait-singleton.php';


    add_action( 'plugins_loaded', 'ucare\load_text_domain' );

    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucare\add_plugin_action_links' );


    /**
     * Main plugin class
     *
     * @package ucare
     * @since 1.4.2
     */
    class uCare {

        use Data;
        use Singleton;


        protected function initialize() {

            $this->do_defines();
            $this->do_includes();

            // All done
            do_action( 'ucare_loaded', $this );

        }


        private function do_defines() {

            define( 'UCARE_DIR', plugin_dir_path( __FILE__ ) );
            define( 'UCARE_URL', plugin_dir_url(  __FILE__ ) );

            define( 'UCARE_TEMPLATES_PATH', UCARE_DIR . 'templates/' );
            define( 'UCARE_PARTIALS_PATH',  UCARE_DIR . 'templates/partials/' );
            define( 'UCARE_INCLUDES_PATH',  UCARE_DIR . 'includes/'  );

        }


        private function do_includes() {

            include_once dirname( __FILE__ ) . '/lib/mail/mail.php';


            include_once dirname( __FILE__ ) . '/includes/email-notifications.php';
            include_once dirname( __FILE__ ) . '/includes/cron.php';
            include_once dirname( __FILE__ ) . '/includes/extension-licensing.php';


            include_once dirname( __FILE__ ) . '/includes/class-field.php';
            include_once dirname( __FILE__ ) . '/includes/class-bootstrap-nav-walker.php';


            include_once dirname( __FILE__ ) . '/includes/functions.php';
            include_once dirname( __FILE__ ) . '/includes/functions-comment.php';
            include_once dirname( __FILE__ ) . '/includes/functions-user.php';
            include_once dirname( __FILE__ ) . '/includes/functions-metabox.php';
            include_once dirname( __FILE__ ) . '/includes/functions-template.php';
            include_once dirname( __FILE__ ) . '/includes/functions-sanitize.php';
            include_once dirname( __FILE__ ) . '/includes/functions-scripts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-helpers.php';
            include_once dirname( __FILE__ ) . '/includes/functions-deprecated.php';
            include_once dirname( __FILE__ ) . '/includes/functions-widgets.php';
            include_once dirname( __FILE__ ) . '/includes/functions-field.php';
            include_once dirname( __FILE__ ) . '/includes/functions-public.php';
            include_once dirname( __FILE__ ) . '/includes/functions-shortcodes.php';
            include_once dirname( __FILE__ ) . '/includes/functions-post-support_ticket.php';
            include_once dirname( __FILE__ ) . '/includes/functions-taxonomy-ticket_category.php';


            if ( is_admin() ) {
                include_once dirname( __FILE__ ) . '/includes/admin/functions-menu.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-settings.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-admin-bar.php';
            }


            if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
                include_once dirname( __FILE__ ) . '/lib/license/EDD_SL_Plugin_Updater.php';
            }

        }

    }


    function ucare() {
        return uCare::instance();
    }


    function load_text_domain() {
        load_plugin_textdomain( 'ucare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }


    function add_plugin_action_links( $links ) {

        if ( !get_option( Options::DEV_MODE ) ) {
            $links['deactivate'] = sprintf( '<span id="feedback-prompt">%s</span>', $links['deactivate'] );
        }

        $custom = array(
            'settings' => sprintf( '<a href="%1$s">%2$s</a>', menu_page_url( 'uc-settings', false ), __( 'Settings', 'ucare' ) )
        );

        return array_merge( $links, $custom );

    }


    // TODO move this to a plugins_loaded callback
    ucare();

    //<editor-fold desc="Legacy Boot">
    do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );
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
