<?php
/*
 * Plugin Name: Support System
 * Author: Smartcat
 * Description: WordPress integrated support ticketing system
 * 
 */

namespace SmartcatSupport;

use SmartcatSupport\util\Loader;

// Die if access directly
if( !defined( 'ABSPATH' ) ) {
    die();
}


// Plugin-wide constant declarations
const PLUGIN_VERSION = 1;
const TEXT_DOMAIN = 'ca.smartcat.support';


// Manual includes
include_once 'vendor/autoload.php';
include_once  'api.php';


// Kickoff the plugin's init
call_user_func( function () {
    Loader::init( __FILE__ );
} );
