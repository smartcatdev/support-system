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
    
    /**
     *  Get a ticket by ID and return a form populated with the ticket's information to edit it.
     *  
     *  @since 1.0.0
     */
    public function get_ticket() {
        if( isset( $_REQUEST['ticket_id'] ) ) {
            $post = get_post( $_REQUEST['ticket_id'] );
            $user = wp_get_current_user();

            // Make sure the post is a ticket and the user has edit privileges
            if( isset( $post ) && $post->post_type == Ticket::POST_TYPE && 
                ( $post->post_author == $user->ID || in_array( Role::AGENT, $user->roles ) ) ) {
                
                // Setup the form with the post's data
                $form = $this->ticket_builder->configure( $post );
                $info_form = null;
        
                // If the user is a support agent, setup the meta form
                if( in_array( Role::AGENT, $user->roles ) ) {
                    $info_form = $this->info_builder->configure( $post );
                }
                
                // Display the ticket status for users
                $status = null;
                
                if( in_array( Role::USER, $user->roles ) ) {
                    $status = Ticket::status_list()[ get_post_meta( $post->ID, 'status', true ) ];
                }

                // Save the index of the current ticket the user is editing
                update_user_meta( $user->ID, 'current_ticket', $post->ID );

                // Send the edit form back to the user
                wp_send_json_success( 
                    $this->view->render( [ 
                        'ticket_form'   => $form, 
                        'info_form'     => $info_form,
                        'ajax_action'   => 'update_ticket',
                        'status'        => $status,
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
     * 
     *  @since 1.0.0
     */
    public function create_ticket() {
        
        // Configure forms without set data
        $form = $this->ticket_builder->configure();
        $info_form = null;
        
        // If the user is a support agent, setup the meta form
        if( in_array( Role::AGENT, wp_get_current_user()->roles ) ) {
            $info_form = $this->info_builder->configure();
        }
        
        wp_send_json_success( 
            $this->view->render( [ 
                'ticket_form'   => $form, 
                'info_form'     => $info_form,
                'ajax_action'   => 'update_ticket' 
            ] ) 
        );
    }

    /**
     *  Create a new ticket or update an existing one.
     * 
     *  @since 1.0.0
     */
    public function save_ticket() {
        $user = wp_get_current_user();
        $form = $this->ticket_builder->configure();
        $info_form = null;
        $post_id = false;
        
        // If the user is a support agent, setup the meta form
        if( in_array( Role::AGENT, $user->roles ) ) {
            $info_form = $this->info_builder->configure();
        }
        
        if( $form->is_valid() ) {
            $data = $form->get_data();
            
            // If ID from the form matches the current, the user is updating, else its a new ticket
            $ticket_id = $data['ticket_id'] == get_user_meta( $user->ID, 'current_ticket', true ) ? $data['ticket_id'] : null;
            
            $post_id = wp_insert_post( [
                'ID'                => $ticket_id,
                'post_title'        => $data['title'],
                'post_content'      => $data['content'],
                'post_status'       => 'publish',
                'post_type'         => Ticket::POST_TYPE,
                
                // Don't change the author if updating
                'post_author'       => isset( $ticket_id ) ? get_post( $ticket_id )->post_author : null,
                'comment_status'    => 'open'
            ] );
            
            // If valid insert and the user is a support agent save all the meta fields
            if( $post_id > 0 && in_array( Role::AGENT, $user->roles ) ) {
                if( $info_form->is_valid() ) {
                    $data = $info_form->get_data();
                    
                    foreach( $data as $key => $value ) {
                        update_post_meta( $post_id, $key, $value );
                    } 
                } else {
                    wp_send_json_error( "error" );
                }
              
                // If its a new ticket and the user is a support user, only save their email address and the date
            } else if( is_null( $ticket_id ) && in_array( Role::USER, $user->roles ) ) {
                update_post_meta( $post_id, 'email', $user->user_email );
                update_post_meta( $post_id, 'date', date( 'Y-m-d' ) );
            }
        } else {
           wp_send_json_error( "error" );
        }
    }
}
