<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\template\View;
use SmartcatSupport\TicketPostFormBuilder;
use SmartcatSupport\TicketInfoFormBuilder;
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
    private $ticket_builder;
    private $info_builder;

    public function __construct( TicketPostFormBuilder $ticket_builder, TicketInfoFormBuilder $info_builder, View $view ) {
        $this->view = $view;
        $this->ticket_builder = $ticket_builder;
        $this->info_builder = $info_builder;
        
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
                
                $form = $this->ticket_builder->configure( $post );
                $info_form = $this->info_builder->configure( $post );

                // Save the index of the current ticket the user is editing
                update_user_meta( wp_get_current_user()->ID, 'current_ticket', $post->ID );

                wp_send_json_success( 
                    $this->view->render( [ 
                        'ticket_form' => $form, 
                        'info_form' => in_array( Role::AGENT, $user->roles ) ? $info_form : null,
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
        $form = $this->ticket_builder->configure();
        $info_form = $this->info_builder->configure();
        
        wp_send_json_success( 
            $this->view->render( [ 
                'ticket_form' => $form, 
                'info_form' => in_array( Role::AGENT, wp_get_current_user()->roles ) ? $info_form : null,
                'ajax_action' => 'update_ticket' 
            ] ) 
        );
    }

    public function save_ticket() {
        $form = $this->ticket_builder->configure();
        $info_form = $this->info_builder->configure();
        
        $user = wp_get_current_user();
        
        $post_id = false;
        
        if( $form->is_valid() ) {
            $data = $form->get_data();
            
            $post_id = wp_insert_post( [
                // If the user is updating the current ticket use it's ID, else insert a new ticket
                'ID' => $data['ticket_id'] == get_user_meta( $user->ID, 'current_ticket', true ) ? $data['ticket_id'] : null,
                'post_title'        => $data['title'],
                'post_content'      => $data['content'],
                'post_status'       => 'publish',
                'post_type'         => Ticket::POST_TYPE,
                'post_author'       => is_null( $data['ticket_id'] ) ? $user->ID : null,
                'comment_status'    => 'open'
            ] );
        }

        if( in_array( Role::AGENT, $user->roles ) ) {
            if( $info_form->is_valid() ) {
                $data = $info_form->get_data();

                foreach( $data as $key => $value ) {
                    update_post_meta( $data['ticket_id'], $key, $data[ $key ] );
                } 
            }
        } else if( in_array( Role::USER, $user->roles ) ) {
            update_post_meta( $post_id, 'email', $user->user_email );
            update_post_meta( $post_id, 'date', date( 'Y-m-d' ) );
        }
        

 //            if( $result > 0 ) {
//                wp_send_json_success();
//            } else {
//                wp_send_json_error();
//            }
    }
}
