<?php
/*
 * Plugin Name: Support System
 * Author: Smartcat
 * Description: WordPress integrated support ticketing system
 * 
 */

namespace SmartcatSupport;

use SmartcatSupport\Util\Loader;

// Die if access directly
if( !defined( 'ABSPATH' ) ) {
    die();
}

include_once 'vendor/autoload.php';
include_once 'constants.php';

function vroom_vroom() {
    return Loader::init( __FILE__ );
}

vroom_vroom();