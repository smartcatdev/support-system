<?php
/*
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: A full blown support ticket system, with notifications and user roles. Compatible with WooCommerce and Easy Digital Downloads 
 * Version: 1.0
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * License: GPL V2
 * 
 * 
 */

namespace SmartcatSupport;

// Die if access directly
if( !defined( 'ABSPATH' ) ) {
    die();
}


// Manual includes
include_once 'vendor/autoload.php';


// Boot up the container
Plugin::boot( Plugin::ID, Plugin::VERSION, __FILE__ );
