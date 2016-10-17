<?php

namespace SmartcatSupport\util;

use SmartcatSupport\ticket\TicketMetaBox;
use SmartcatSupport\form\Form;
use SmartcatSupport\form\SelectBox;
use SmartcatSupport\form\Email;
use SmartcatSupport\form\Date;

/**
 * Loader to initialize the plugin
 */
final class Loader {
    private static $instance;
    
    private $installer;     
    private $ticket_metabox;
    
    private function __construct() {
        $this->installer = new Installer();
    }
    
    public static function init( $file ) {
        if( self::$instance == null ) {
            self::$instance = new self();
            
            register_activation_hook( $file, [ self::$instance->installer, 'activate' ] );
            register_deactivation_hook( $file, [ self::$instance->installer, 'deactivate' ] );
            
            self::$instance->init_ticket_metabox();
        }
        
        return self::$instance;
    }
    
    private function init_ticket_metabox() {
            $form = new Form( 'ticket_meta' );
            $form->add_field( 'email_address', new Email( 'email_address', 'Contact Email' ) );
            $form->add_field( 'assigned_to', new SelectBox( 'assigned_to', 'Assigned To', [ 'options' => TicketMetaBox::agent_list() ] ) );
            $form->add_field( 'status', new SelectBox( 'status', 'Status', [ 'options' => TicketMetaBox::status_list() ] ) );
            $form->add_field( 'date_opened', new Date( 'date_opened', 'Date Opened' ) );
            
            $this->ticket_metabox = new TicketMetaBox( $form );
    }
}