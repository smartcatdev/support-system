<?php

namespace SmartcatSupport;

use SmartcatSupport\form\Builder;
use SmartcatSupport\form\TextBox;
use SmartcatSupport\form\SelectBox;
use SmartcatSupport\Ticket;
use SmartcatSupport\form\validation\Selection;
use SmartcatSupport\form\validation\Date;

/**
 * Description of TicketMetaBoxFormBuilder
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class InfoFormBuilder extends Builder {
    
    public function configure( \WP_Post $post ) {
        $this->add( TextBox::class, 'email',
            [ 
                'type' => 'email',
                'label' => 'Contact Email',
                'value' => get_post_meta( $post->ID, 'email', true ),
            ] 
        )->add( SelectBox::class, 'agent', 
            [ 
                'label' => 'Assigned To',
                'options' => Ticket::agent_list(),
                'value' => get_post_meta( $post->ID, 'agent', true ),
                'constraints' => [ 
                    $this->create_constraint( Selection::class, '', Ticket::agent_list() ) 
                ]
            ] 
        )->add( SelectBox::class, 'status',
            [ 
                'label' => 'Status',
                'options' => Ticket::status_list(),
                'value' => get_post_meta( $post->ID, 'status', true ),
                'constraints' => [ 
                    $this->create_constraint( Selection::class, '', Ticket::status_list() ) 
                ]
            ] 
        )->add( TextBox::class, 'date_opened',
            [ 
                'label' => 'Date Opened',
                'type' => 'date',
                'value' => get_post_meta( $post->ID, 'date_opened', true ),
                'constraints' => [ 
                    $this->create_constraint( Date::class ) 
                ]
            ] 
        );
        
        return $this->get_form();
    }
}
