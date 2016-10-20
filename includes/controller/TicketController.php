<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\template\View;
use SmartcatSupport\TicketPostFormBuilder;
use SmartcatSupport\ActionListener;
use SmartcatSupport\Ticket;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class TicketController extends ActionListener {
    private $view;
    private $builder;

    public function __construct( TicketPostFormBuilder $builder, View $view ) {
        $this->view = $view;
        $this->builder = $builder;
        
        $this->add_ajax_action( 'update_ticket', 'process_request' );
    }
    
    // TODO temporary for render call from shortcode
    public function render() {
       $this->process_request();
    }
    
    public function process_request( $post_id = -1 ) {
       $form = $this->builder->configure( get_post( $post_id ) );

       if( !$form->is_submitted() ) {
           if( $form->is_valid() ) {
                $data = $form->get_data();

                $result = wp_insert_post( [
                    'ID' => isset( $data['post_id'] ) ? $data['post_id'] : null,
                    'post_title'        => $data['title'],
                    'post_content'      => $data['content'],
                    'post_status'       => 'publish',
                    'post_type'         => Ticket::POST_TYPE,
                    'post_author'       => is_null( $data['post_id'] ) ? wp_get_current_user()->ID : null,
                    'comment_status'    => 'open'
                ] );

                // TODO return proper json object
                echo 0;
            }    
       } else {       
            echo $this->view->render( [ 
                 'ticket_form' => $form, 
                 'ajax_action' => 'update_ticket' 
            ] );  
        }
        
    }
}
