<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\template\View;
use SmartcatSupport\TicketPostFormBuilder;
use SmartcatSupport\ActionListener;
use SmartcatSupport\Ticket;
use SmartcatSupport\admin\Role;

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
        if( isset( $_REQUEST['ticket_id'] ) ) {
            $post = get_post( $_REQUEST['ticket_id'] );

            $user = wp_get_current_user();
            
            if( $post->post_type == Ticket::POST_TYPE && 
                    ( $post->post_author == $user->ID || 
                    in_array( Role::AGENT, $user->roles ) ) ) {
                
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
                wp_send_json_error( "error" );
            }
        } else {
            wp_send_json_error( "error" );
        }
    }
    
    /**
     *  Send a blank ticket form.
     */
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
            
            if( $result > 0 ) {
                wp_send_json_success();
            } else {
                wp_send_json_error();
            }
        }
    }
}
