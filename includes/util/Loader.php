<?php

namespace SmartcatSupport\util;

use SmartcatSupport\ticket\TicketMetaBox;

/**
 * Loader to initialize the plugin
 */
final class Loader {
    private static $instance;
    
    private $installer;
    private $ticket_metabox;
    
    private function __construct() {
        $this->installer = new Installer();
        $this->ticket_metabox = new TicketMetaBox();
    }
    
    public static function init( $file ) {
        if( self::$instance == null ) {
            self::$instance = new self();
            
            register_activation_hook( $file, [ self::$instance->installer, 'activate' ] );
            register_deactivation_hook( $file, [ self::$instance->installer, 'deactivate' ] );
        }
        
        return self::$instance;
    }
}