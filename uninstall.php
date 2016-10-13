<?php

namespace SmartcatSupport;

use SmartcatSupport\Enum\Option;

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

if( get_option( Options::NUKE, false ) ) {
    //delete everything
}