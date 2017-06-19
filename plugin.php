<?php
/*
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress. 
 * Version: 1.3.0
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

const PLUGIN_ID = 'smartcat_support';
const PLUGIN_VERSION = '1.3.0';
const MIN_PHP_VERSION = '5.5';

if( PHP_VERSION >= MIN_PHP_VERSION ) {

    // Pull in manual includes
    include_once 'loader.php';

    // Boot up the container
    Plugin::boot( PLUGIN_ID, PLUGIN_VERSION, __FILE__ );

} else {

    add_action( 'admin_notices', function () { ?>

        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Your PHP version ' . PHP_VERSION . ' does not meet minimum requirements. uCare Support requires version 5.5 or higher', 'ucare' ); ?></p>
        </div>

    <?php } );

    add_action( 'admin_init', function () {

        deactivate_plugins( plugin_basename( __FILE__ ), true );

    } );

}
