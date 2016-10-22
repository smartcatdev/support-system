<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\TicketMetaFormBuilder;
use SmartcatSupport\abstracts\MetaBox;
use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Metabox for support ticket information.
 * 
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
class SupportTicketMetaBox extends MetaBox {
    
    /**
     * @var TicketInfoFormBuilder
     * @since 1.0.0
     */
    private $builder;
    
    /**
     * @param TicketInfoFormBuilder $builder Configures the form for the metabox.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( TicketMetaFormBuilder $builder ) {
        parent::__construct( 'ticket_meta', __( 'Ticket Information', TEXT_DOMAIN ), 'support_ticket' ); 

        $this->builder = $builder;
    }
    
    public function render( $post ) {
        $form = $this->builder->configure( $post );
        
        Form::form_fields( $form );
    }

    public function save( $post_id, $post ) {
        $form = $this->builder->configure();
        
        if( $form->is_valid() ) {
            $data = $form->get_data();
                    
            foreach( $data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
