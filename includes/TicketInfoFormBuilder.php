<?php

namespace SmartcatSupport;

use SmartcatSupport\form\Builder;
use SmartcatSupport\form\TextBox;
use SmartcatSupport\form\SelectBox;
use SmartcatSupport\Ticket;
use SmartcatSupport\form\validation\InArray;
use SmartcatSupport\form\validation\Date;

/**
 * Description of TicketMetaBoxFormBuilder
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class TicketInfoFormBuilder extends Builder {
    
    public function configure( \WP_Post $post = null ) {
        $this->add( TextBox::class, 'email',
            [ 
                'type' => 'email',
                'label' => 'Contact Email',
                'value' => isset( $post ) ? get_post_meta( $post->ID, 'email', true ) : '',
            ] 
        )->add( SelectBox::class, 'agent', 
            [ 
                'label' => 'Assigned To',
                'options' => Ticket::agent_list(),
                'value' => isset( $post ) ? get_post_meta( $post->ID, 'agent', true ) : '',
                'constraints' => [ 
                    $this->create_constraint( InArray::class, '', Ticket::agent_list() ) 
                ]
            ] 
        )->add( SelectBox::class, 'status',
            [ 
                'label' => 'Status',
                'options' => Ticket::status_list(),
                'value' => isset( $post ) ? get_post_meta( $post->ID, 'status', true ) : '',
                'constraints' => [ 
                    $this->create_constraint( InArray::class, '', Ticket::status_list() ) 
                ]
            ] 
        )->add( TextBox::class, 'date_opened',
            [ 
                'label' => 'Date Opened',
                'type' => 'date',
                'value' => isset( $post ) && get_post_meta( $post->ID, 'date_opened', true ) != '' ? get_post_meta( $post->ID, 'date_opened', true ) : date( 'Y-m-d' ),
                'constraints' => [ 
                    $this->create_constraint( Date::class ) 
                ]
            ] 
        );
        
        return $this->get_form();
    }
}
