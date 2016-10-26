<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\util\View;
use SmartcatSupport\template\TicketFormBuilder;
use SmartcatSupport\template\TicketMetaFormBuilder;
use SmartcatSupport\util\ActionListener;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class TicketHandler extends ActionListener {
    private $view;
    private $ticket_form_builder;
    private $meta_form_builder;
    
    private static $SINGLE_VIEW = 'edit_ticket';
    private static $LIST_VIEW = 'ticket_list';
    
    public function __construct( TicketFormBuilder $ticket_form_builder, TicketMetaFormBuilder $meta_form_builder, View $view ) {
        $this->view = $view;
        $this->ticket_form_builder = $ticket_form_builder;
        $this->meta_form_builder = $meta_form_builder;
        
        $this->add_ajax_action( 'create_ticket', 'create_ticket' );
        $this->add_ajax_action( 'update_ticket', 'save_ticket' );
        $this->add_ajax_action( 'get_ticket', 'get_ticket' );
        $this->add_ajax_action( 'ticket_list', 'ticket_list' );
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
            if( isset( $post ) && $post->post_type == 'support_ticket' && 
                ( $post->post_author == $user->ID || current_user_can( 'edit_others_tickets' ) ) ) {
                
                $form = $this->ticket_form_builder->configure( $post );
                $info_form = null;
        
                if( current_user_can( 'edit_ticket_meta' ) ) {
                    $info_form = $this->meta_form_builder->configure( $post );
                }

                // Save the index of the current ticket the user is editing
                update_user_meta( $user->ID, 'current_ticket', $post->ID );

                wp_send_json_success( 
                    $this->view->render( self::$SINGLE_VIEW, [ 
                        'ticket_form'   => $form, 
                        'info_form'     => $info_form,
                        'ajax_action'   => 'update_ticket',
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
        $form = $this->ticket_form_builder->configure();
        $info_form = null;
        
        if( current_user_can( 'edit_ticket_meta' ) ) {
            $info_form = $this->meta_form_builder->configure();
        }
        
        wp_send_json_success( 
            $this->view->render( self::$SINGLE_VIEW, [ 
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
        $form = $this->ticket_form_builder->configure();
        $user = wp_get_current_user();
        $post_id = false;
        
        if( $form->is_valid() ) {
            $data = $form->get_data();

            // If ID from the form matches the current, the user is updating, else its a new ticket
            $ticket_id = $data['ticket_id'] == get_user_meta( $user->ID, 'current_ticket', true ) ? $data['ticket_id'] : null;
            
            $post_id = wp_insert_post( [
                'ID'                => $ticket_id,
                'post_title'        => $data['title'],
                'post_content'      => $data['content'],
                'post_status'       => 'publish',
                'post_type'         => 'support_ticket',
                
                // Don't change the author if updating
                'post_author'       => isset( $ticket_id ) ? get_post( $ticket_id )->post_author : null,
                'comment_status'    => 'open'
            ] );
            
            // If valid insert and the user is a support agent save all the meta fields
            if( $post_id > 0 && current_user_can( 'edit_ticket_meta' ) ) {
                $info_form = $this->meta_form_builder->configure();
                
                if( $info_form->is_valid() ) {
                    $data = $info_form->get_data();
                    
                    foreach( $data as $key => $value ) {
                        update_post_meta( $post_id, $key, $value );
                    } 
                } else {
                    wp_send_json_error( "error" );
                }
              
              // If its a new ticket and the user is a support user, only save their email address and the date
            } else if( is_null( $ticket_id ) && in_array( 'support_user', $user->roles ) ) {
                update_post_meta( $post_id, 'email', $user->user_email );
                update_post_meta( $post_id, 'date', date( 'Y-m-d' ) );
            }
        } else {
           wp_send_json_error( "error" );
        }
    }
    
    public function ticket_list() {
        $query = [
            'post_type' => 'support_ticket', 
            'status'    => 'publish',
            
       ];
        
        $results = new \WP_Query( $query );
        
        wp_send_json_success( 
            $this->view->render( self::$LIST_VIEW,
                [
                    'wp_query' => $results
                ]  
            ) 
        );
    }
    
    public function render_dash() {
        echo $this->view->render( 'dash' );
    }
    
    // user is a
    
    // send page
    
    
}
