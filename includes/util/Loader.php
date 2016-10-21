<?php

namespace SmartcatSupport\util;

use SmartcatSupport\admin\TicketInfoMetaBox;
use SmartcatSupport\TicketInfoFormBuilder;
use SmartcatSupport\TicketPostFormBuilder;
use SmartcatSupport\template\View;
use SmartcatSupport\controller\TicketController;
use const SmartcatSupport\PLUGIN_VERSION;

/**
 * Initializes plugin classes and configures dependencies
 * 
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 */
final class Loader {
    private static $instance;
    
    private $plugin_dir;
    private $plugin_dir_url;
    private $ticket_metabox;
    private $ticket_controller;

    private function __construct( $file ) {
        $this->plugin_dir = plugin_dir_path( $file );
        $this->plugin_dir_url = plugin_dir_url( $file );
        
        $this->installer = new Installer();
        $this->ticket_metabox = new TicketInfoMetaBox( new TicketInfoFormBuilder( 'ticket_info' ) );
        $this->ticket_controller = new TicketController(
            new TicketPostFormBuilder( 'ticket' ),
            new View( $this->plugin_dir . 'templates/singleticket.php' )
        );
        
        
        // TODO temporary shortcode assignment
        add_shortcode( 'support-system', [  new View( $this->plugin_dir . 'templates/tickets.php' ) , 'render' ]  );
        
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }
    
    public static function init( $file ) {
        if( self::$instance == null ) {
            self::$instance = new self( $file );
            
            register_activation_hook( $file, [ self::$instance->installer, 'activate' ] );
            register_deactivation_hook( $file, [ self::$instance->installer, 'deactivate' ] );
        }
        
        return self::$instance;
    }
    
    public function enqueue_scripts() {
        wp_register_script( 'support_system_ajax', $this->plugin_dir_url . 'assets/js/ajax.js', [ 'jquery' ], PLUGIN_VERSION );
        wp_localize_script( 'support_system_ajax', 'SmartcatSupport', [ 'ajaxURL' => admin_url( 'admin-ajax.php' ) ] );
        wp_enqueue_script( 'support_system_ajax' );
    }
}