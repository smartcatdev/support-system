<?php

namespace SmartcatSupport\ticket;

use SmartcatSupport\ticket\Ticket;
use SmartcatSupport\ticket\TicketMetaBox;
use SmartcatSupport\contract\AjaxActionListener;
use SmartcatSupport\contract\Role;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Front-end conroller for ticket
 */
class TicketConroller extends AjaxActionListener {
    
    public function save() {
        $errors = [];
        $data = [
            'post_type' => TICKET_POST_TYPE,
            'comment_status' => 'open'
        ];
        
        if( wp_verify_nonce( $nonce, $action ) && isset( $_POST[ 'data' ] ) ) {
            if( isset( $_POST[ 'data' ][ 'id' ] ) && is_numeric( $_POST[ 'data' ][ 'id' ] ) ) {
                $data[ 'ID' ] = $_POST[ 'data' ][ 'id' ];
            }

            if( isset( $_POST[ 'data' ][ 'content' ] ) ) {
                $data[ 'post_content' ] = $_POST[ 'data' ][ 'content' ];
            } else {
                $errors[] = __( 'Description cannot be empty', TEXT_DOMAIN );
            }

            if( isset( $_POST[ 'data' ][ 'title' ] ) ) {
                $data[ 'post_title' ] = $_POST[ 'data' ][ 'title' ];
            } else {
                $errors[] = __( 'Title cannot be empty', TEXT_DOMAIN );
            }

            if( isset( $_POST[ 'data' ][ 'status' ] ) ) {
                $_POST[ 'post_data' ][ 'status' ];
            }
            

        } else {
            $errors[] = __( 'Action not permitted', TEXT_DOMAIN );
        }
       
        if( empty( $messages ) ) {
            wp_insert_post( $data );
            wp_send_json_success( __( 'Your ticket has been submitted', TEXT_DOMAIN ) );
        } else {
            wp_send_json_error( $errors );
        }
    }
    
    public function get() {
        $post = null;
        
        if( isset( $_POST[ 'ticket_id' ] ) ) {
            $post = get_post( $_POST[ 'ticket_id' ], ARRAY_A, 'display' );
        }
        
        if( $post != null ) {
            wp_send_json( json( $post ) ); 
        } else {
            wp_send_json_error( __( 'Post was not found', TEXT_DOMAIN ) );
        }
    }
    
    public function get_meta() {
        $meta = [];
        
        if( current_user_can( Role::CAP_MANAGE ) ) {

        } else { //if new ticket
            $meta[ 'email_address' ] = wp_get_current_user()->user_email;
            $meta[ 'assigned_to' ] = '';
            $meta[ 'status' ] = '';
            $meta[ 'date_opened' ] = '';
        }
        
        return $meta;
    }
}
