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

include_once 'vendor/autoload.php';
include_once 'constants.php';

call_user_func( function () {
    Loader::init( __FILE__ );
} );