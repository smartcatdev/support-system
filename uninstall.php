<?php

namespace SmartcatSupport;

include_once 'vendor/autoload.php';

use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

if( get_option( Option::NUKE, Option\Defaults::NUKE ) == 'on' ) {

    Mailer::cleanup( true );


    // Trash all support tickets
    $query = new \WP_Query( array( 'post_type' => 'support_ticket' ) );

    foreach( $query->posts as $post ) {
        wp_trash_post( $post->ID );
    }


    // Cleanup wp_options
    $options = new \ReflectionClass( Option::class );

    foreach( $options->getConstants() as $option ) {
        delete_option( $option );
    }
}
