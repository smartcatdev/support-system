<?php

namespace SmartcatSupport\ticket;

use SmartcatSupport\admin\MetaBox;
use SmartcatSupport\ticket\Ticket;
use SmartcatSupport\admin\Role;
use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

class TicketMetaBox extends MetaBox {
    private $form;
    
    public function __construct( Form $form ) {
        parent::__construct( 'ticket_meta', __( 'Ticket Information', TEXT_DOMAIN ), Ticket::POST_TYPE );
        
        $this->set_form( $form );
    }
    
    public function get_form() {
        return $this->form;
    }

    public function set_form( $form ) {
        $this->form = $form;
    }

    public function render( $post ) {
        $this->set_form_values( $post->ID );
        $this->form->render();
    }

    public function save( $post_id, $post ) {
        $this->set_form_values( $post_id );
        
        $values = $this->form->validate();
        
        if( $values !== false ) {
            foreach( $values as $meta_key => $value ) {
                update_post_meta( $post_id, $meta_key, $value );
            }
        }
    }
   
    /**
     * Sets the metabox defaults syncronously when the post is loaded / saved
     * 
     * @param type $post_id
     */
    private function set_form_values( $post_id ) {
        foreach( $this->form->get_fields() as $id => $field ) {
            $field->set_default( 
                get_post_meta( $post_id, $field->get_id(), true )
            );
        }
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
