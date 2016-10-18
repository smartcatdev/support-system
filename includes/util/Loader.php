<?php

namespace SmartcatSupport\util;

use SmartcatSupport\admin\InfoMetaBox;
use SmartcatSupport\InfoFormBuilder;

/**
 * Initializes plugin classes and configures dependencies
 * 
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 */
final class Loader {
    private static $instance;
    
    private $fs_context;
    private $ticket_metabox;

    private function __construct( $file ) {
        $this->fs_context = plugin_dir_path( $file );
        $this->installer = new Installer();
        $this->ticket_metabox = new InfoMetaBox( new InfoFormBuilder( 'ticket_info' ) );
    }
    
    public static function init( $file ) {
        if( self::$instance == null ) {
            self::$instance = new self( $file );
            
            register_activation_hook( $file, [ self::$instance->installer, 'activate' ] );
            register_deactivation_hook( $file, [ self::$instance->installer, 'deactivate' ] );
        }
        
        return self::$instance;
    }
}