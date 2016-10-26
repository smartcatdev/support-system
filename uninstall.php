<?php

namespace SmartcatSupport;

use SmartcatSupport\descriptor\Option;

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

if( !empty( get_option( Option::NUKE, Option\Defaults::NUKE ) ) ) {
    //delete everything
}