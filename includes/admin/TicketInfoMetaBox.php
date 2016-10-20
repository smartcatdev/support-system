<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\TicketInfoFormBuilder;
use SmartcatSupport\admin\MetaBox;
use SmartcatSupport\form\Form;
use SmartcatSupport\Ticket;
use const SmartcatSupport\TEXT_DOMAIN;

class TicketInfoMetaBox extends MetaBox {
    private $builder;
    
    public function __construct( TicketInfoFormBuilder $builder ) {
        parent::__construct( 'ticket_meta', __( 'Ticket Information', TEXT_DOMAIN ), Ticket::POST_TYPE ); 
        
        $this->builder = $builder;
    }
    
    public function process_request( $post, $action ) {
        $form = $this->builder->configure( $post );
        
        switch( $action ) {
            case 'render':
                Form::form_fields( $form );
                break;
            
            case 'save':
                if( $form->is_valid() ) {
                    $data = $form->get_data();
                    
                    foreach( $data as $key => $value ) {
                        update_post_meta( $post->ID, $key, $data[ $key ] );
                    }
                }
                
                break;
        }
    }
    
    public function render( $post ) {
        $this->process_request( $post, 'render' );
    }

    public function save( $post_id, $post ) {
        $this->process_request( $post, 'save' );
    }
}
