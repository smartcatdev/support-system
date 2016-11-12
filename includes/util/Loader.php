<?php

namespace SmartcatSupport\util;

use SmartcatSupport\admin\SupportTicketMetaBox;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\template\TicketFormBuilder;
use SmartcatSupport\controller\TicketHandler;
use SmartcatSupport\controller\TableHandler;
use SmartcatSupport\controller\CommentHandler;
use const SmartcatSupport\PLUGIN_VERSION;

/**
 * Initializes plugin classes and configures dependencies
 * 
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 */
final class Loader extends ActionListener {
    private static $instance;
    
    private $plugin_dir;
    private $plugin_url;
    private $templates_dir;
    
    private $ticket_metabox;
    private $ticket_controller;
    private $table_controller;
    private $comment_controller;

    private function __construct( $file ) {
        $this->plugin_dir = plugin_dir_path( $file );
        $this->templates_dir = $this->plugin_dir . 'templates';
        $this->plugin_url = plugin_dir_url( $file );

        $this->installer = new Installer();

        $view = new View( $this->templates_dir );

        $this->ticket_metabox = new SupportTicketMetaBox( $view, new FormBuilder( 'ticket_info' ) );
        $this->ticket_controller = new TicketHandler( $view, new FormBuilder( 'ticket_form' ) );
        $this->comment_controller = new CommentHandler( $view, new FormBuilder( 'comment_form' ) );
        $this->table_controller = new TableHandler( $view );


        // TODO temporary shortcode assignment
        add_shortcode( 'support-system', [  $this->ticket_controller , 'render_dash' ]  );
        
        $this->add_action( 'wp_enqueue_scripts', 'enqueue_assets' );
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

        wp_enqueue_script( 'tabular', $this->plugin_url . 'assets/lib/tabular.js', [ 'jquery' ], PLUGIN_VERSION );
        wp_enqueue_script( 'tinymce_js', includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', [ 'jquery' ], false, true );

        wp_register_script( 'support_system_lib', $this->plugin_url . 'assets/js/app.js', [ 'jquery' ], PLUGIN_VERSION );
        wp_localize_script( 'support_system_lib', 'SupportSystem', [ 'ajaxURL' => admin_url( 'admin-ajax.php' ), 'assetsURL' => $this->plugin_url . 'assets' ] );
        wp_enqueue_script( 'support_system_lib' );

        wp_enqueue_script( 'support_system_script', $this->plugin_url . 'assets/js/script.js', [ 'jquery', 'support_system_lib' ], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_style', $this->plugin_url . 'assets/css/style.css', [], PLUGIN_VERSION );
        wp_enqueue_style( 'support_system_icons', $this->plugin_url . 'assets/icons.css', [], PLUGIN_VERSION );

    }
}
