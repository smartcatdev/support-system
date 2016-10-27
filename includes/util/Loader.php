<?php

namespace SmartcatSupport\util;

use SmartcatSupport\admin\SupportTicketMetaBox;
use SmartcatSupport\template\TicketMetaFormBuilder;
use SmartcatSupport\template\TicketFormBuilder;
use SmartcatSupport\controller\TicketHandler;
use SmartcatSupport\controller\TicketTableHandler;
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
    private $plugin_url;
    private $templates_dir;
    
    private $ticket_metabox;
    private $ticket_controller;
    private $table_controller;

    private function __construct( $file ) {
        $this->plugin_dir = plugin_dir_path( $file );
        $this->templates_dir = $this->plugin_dir . 'templates';
        $this->plugin_url = plugin_dir_url( $file );


        $this->installer = new Installer();
        
        $this->ticket_metabox = new SupportTicketMetaBox(
            new View( $this->templates_dir ),
            new TicketMetaFormBuilder( 'ticket_info' )
        );
        
        $this->ticket_controller = new TicketHandler(
            new View( $this->templates_dir ),
            new TicketFormBuilder( 'ticket_form' )
        );

        $this->table_controller = new TicketTableHandler(
            new View( $this->templates_dir )
        );


        // TODO temporary shortcode assignment
        add_shortcode( 'support-system', [  $this->ticket_controller , 'render_dash' ]  );
        
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }
    
    public static function init( $file ) {
        if( self::$instance == null ) {
            self::$instance = new self( $file );
            
            register_activation_hook( $file, [ self::$instance->installer, 'activate' ] );
            register_deactivation_hook( $file, [ self::$instance->installer, 'deactivate' ] );
        }
        
        return self::$instance;
    }
    
    public function enqueue_assets() {
        wp_enqueue_script( 'datatables', $this->plugin_url . 'assets/lib/datatables/js/datatables.min.js', [ 'jquery' ], PLUGIN_VERSION );
        wp_enqueue_style( 'datatables', $this->plugin_url . 'assets/lib/datatables/css/datatables.min.css', [], PLUGIN_VERSION );
        
        wp_register_script( 'support_system_ajax', $this->plugin_url . 'assets/js/ajax.js', [ 'jquery' ], PLUGIN_VERSION );
        wp_localize_script( 'support_system_ajax', 'SmartcatSupport', [ 'ajaxURL' => admin_url( 'admin-ajax.php' ) ] );
        wp_enqueue_script( 'support_system_ajax' );

        wp_enqueue_style( 'support_system_style', $this->plugin_url . 'assets/css/style.css', [], PLUGIN_VERSION );
    }
}
