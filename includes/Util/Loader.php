<?php

namespace SmartcatSupport\Util;

final class Loader {
    private $file;
    
    public function __construct( $file ) {
        $this->file = $file;
    }
    
    public static function init( $file ) {
        $loader = new self( $file );
        
        $loader->define_constants();
        $loader->initialize();   
    }
    
    private function define_constants() {
        if( !defined( 'SC_SUPPORT_FILE' ) ) {
            define( 'SC_SUPPORT_FILE', $this->file );
        }
        
        if( !defined( 'SC_SUPPORT_PATH' ) ) {
            define( 'SC_SUPPORT_PATH', plugin_dir_path( $this->file ) );
        }
        
        if( !defined( 'SC_SUPPORT_URL' ) ) {
            define( 'SC_SUPPORT_URL', plugin_dir_url( $this->file ) );
        }
    }
    
    private function initialize() {
        Install::install();  
    }
}