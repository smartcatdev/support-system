<?php

namespace SmartcatSupport;

use SmartcatSupport\form\Builder;
use SmartcatSupport\form\TextBox;
use SmartcatSupport\form\TextArea;
use SmartcatSupport\form\Hidden;

class TicketPostFormBuilder extends Builder {

    public function configure( \WP_Post $post = null ) {
        $this->add( TextBox::class, 'title',
            [ 
                'label' => 'Subject',
                'desc' => 'The subject of the ticket',
                'value' => is_null( $post ) ? '' : $post->post_title,
            ] 
        )->add( TextArea::class, 'content', 
            [ 
                'label' => 'Description',
                'desc' => 'The description of your issue',
                'value' => is_null( $post ) ? '' : $post->post_content,
            ] 
        )->add( Hidden::class, 'ticket_id',
            [
                'value' => is_null( $post ) ? '' : $post->ID
            ]   
        );
        
        return $this->get_form();
    }
}
