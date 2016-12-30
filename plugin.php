<?php
/*
 * Plugin Name: Support System
 * Author: Smartcat
 * Description: WordPress integrated support ticketing system
 */

namespace SmartcatSupport;

// Die if access directly
if( !defined( 'ABSPATH' ) ) {
    die();
}


// Plugin-wide constant declarations
const PLUGIN_VERSION = '1.0';
const TEXT_DOMAIN = 'smartcat_support';


// Manual includes
include_once 'vendor/autoload.php';
include_once 'includes/functions.php';


// Boot up the container
Plugin::boot( TEXT_DOMAIN, PLUGIN_VERSION, __FILE__ );

bootstrap( __FILE__ );
