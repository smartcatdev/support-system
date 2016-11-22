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
const PLUGIN_VERSION = 1;
const TEXT_DOMAIN = 'ca.smartcat.support';


// Manual includes
include_once 'vendor/autoload.php';
include_once 'functions.php';


init( __FILE__ );
