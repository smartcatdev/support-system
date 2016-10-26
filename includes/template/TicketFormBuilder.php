<?php

namespace SmartcatSupport\template;

use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\form\TextBox;
use SmartcatSupport\form\TextArea;
use SmartcatSupport\form\Hidden;

class TicketFormBuilder extends FormBuilder {

    public function configure( \WP_Post $post = null ) {
        $this->add( TextBox::class, 'title',
            [ 
                'label' => 'Subject',
                'desc' => 'The subject of the ticket',
                'value' => isset( $post ) ? $post->post_title : '',
            ] 
        )->add( TextArea::class, 'content',
            [ 
                'label' => 'Description',
                'desc' => 'The description of your issue',
                'value' => isset( $post ) ? $post->post_content : '',
            ] 
        )->add( Hidden::class, 'ticket_id',
            [
                'value' => isset( $post ) ? $post->ID : ''
            ]   
        );

        return $this->get_form();
    }
}
