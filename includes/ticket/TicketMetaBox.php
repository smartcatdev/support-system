<?php

namespace SmartcatSupport\ticket;

use SmartcatSupport\admin\MetaBox;
use SmartcatSupport\ticket\Ticket;
use SmartcatSupport\contract\Role;
use SmartcatSupport\form\Form;
use SmartcatSupport\form\TextBox;
use const SmartcatSupport\TEXT_DOMAIN;

class TicketMetaBox extends MetaBox {
    
    public function __construct() {
        parent::__construct( 'ticket_meta', __( 'Ticket Information', TEXT_DOMAIN ), Ticket::POST_TYPE );
    }

    public function render( $post ) {
        $form = new Form( 'meta' );
        $form->add_field( 'text', new TextBox( 'text', 'Text', ['desc' => 'my cool field'] ) );
        $form->render();
    }

    public function save( $post_id, $post ) {
        
    }
    
    public static function agent_list() {
        $agents[ '' ] = 'No Agent Assigned';
        
        $users = get_users( [ 'role__in' => [ Role::AGENT, Role::ADMIN ] ] );
        
        if( $users != null ) {
            foreach( $users as $user ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }
        
        return $agents;
    }
    
    public static function status_list() {
        return [ 
            'new'           => 'New', 
            'in_progress'   => 'In Progress', 
            'resolved'      => 'Resolved', 
            'follow_up'     => 'Follow Up', 
            'closed'        => 'Closed'
        ]; 
    }
}
