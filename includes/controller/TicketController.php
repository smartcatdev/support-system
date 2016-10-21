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
        
        $this->add_ajax_action( 'create_ticket', 'create_ticket' );
        $this->add_ajax_action( 'update_ticket', 'save_ticket' );
        $this->add_ajax_action( 'get_ticket', 'get_ticket' );
    }
    
    public function get_ticket() {
        $post = get_post( $_REQUEST['ticket_id'] );
        
        if( $post->post_type == Ticket::POST_TYPE ) {
            $form = $this->builder->configure( $post );
        
            // Save the index of the current ticket the user is editing
            update_user_meta( wp_get_current_user()->ID, 'current_ticket', $post->ID );
            
            wp_send_json_success( 
                $this->view->render( [ 
                    'ticket_form' => $form, 
                    'ajax_action' => 'update_ticket' 
                ] ) 
            );
        } else {
            //error
        }
    }
    
    public function create_ticket() {
        $form = $this->builder->configure();
        
        wp_send_json_success( 
            $this->view->render( [ 
                'ticket_form' => $form, 
                'ajax_action' => 'update_ticket' 
            ] ) 
        );
    }

    public function save_ticket() {
        $form = $this->builder->configure();
        
        if( $form->is_valid() ) {
            $data = $form->get_data();
            
            $result = wp_insert_post( [
                // If the user is updating the current ticket use it's ID, else insert a new ticket
                'ID' => $data['ticket_id'] == get_user_meta( wp_get_current_user()->ID, 'current_ticket', true ) ? $data['ticket_id'] : null,
                'post_title'        => $data['title'],
                'post_content'      => $data['content'],
                'post_status'       => 'publish',
                'post_type'         => Ticket::POST_TYPE,
                'post_author'       => is_null( $data['post_id'] ) ? wp_get_current_user()->ID : null,
                'comment_status'    => 'open'
            ] );
            
            echo $result;

            // if post_id 
            //   send success
            // else 
            //   send error
        }
    }
}
