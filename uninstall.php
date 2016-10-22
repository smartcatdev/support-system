<?php

namespace SmartcatSupport;

use SmartcatSupport\desc\Option;

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

if( !empty( get_option( Option::NUKE, Option\Defaults::NUKE ) ) ) {
    //delete everything
}