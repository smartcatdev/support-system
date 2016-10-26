<?php

namespace SmartcatSupport\template;

use SmartcatSupport\form\Builder;
use SmartcatSupport\form\TextBox;
use SmartcatSupport\form\SelectBox;
use SmartcatSupport\form\validation\InArray;
use SmartcatSupport\form\validation\Date;
use SmartcatSupport\descriptor\Option;
/**
 * Description of TicketMetaBoxFormBuilder
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class TicketMetaFormBuilder extends Builder {
    
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
                'options' => $this->agents(),
                'value' => isset( $post ) ? get_post_meta( $post->ID, 'agent', true ) : '',
                'constraints' => [ 
                    $this->create_constraint( InArray::class, '', array_keys( $this->agents() ) ) 
                ]
            ] 
        )->add( SelectBox::class, 'status',
            [ 
                'label' => 'Status',
                'options' => $this->statuses(),
                'value' => isset( $post ) ? get_post_meta( $post->ID, 'status', true ) : '',
                'constraints' => [ 
                    $this->create_constraint( InArray::class, '', array_keys( $this->statuses() ) ) 
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
    
    private function agents() {
        $agents[ '' ] = 'No Agent Assigned';
        
        $users = get_users( [ 'role' => [ 'support_agent' ] ] );
        
        if( $users != null ) {
            foreach( $users as $user ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }
        
        return $agents;
    }
    
    private function statuses() {
        return get_option( Option::STATUSES, Option\Defaults::STATUSES );
    }
}
