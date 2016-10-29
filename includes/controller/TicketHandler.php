<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\util\View;
use SmartcatSupport\template\TicketFormBuilder;
use SmartcatSupport\util\ActionListener;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class TicketHandler extends ActionListener {
    private $view;
    private $form_builder;

    public function __construct( View $view, TicketFormBuilder $form_builder ) {
        $this->view = $view;
        $this->form_builder = $form_builder;

        $this->add_ajax_action( 'edit_support_ticket', 'edit_ticket' );
        $this->add_ajax_action( 'save_support_ticket', 'save_ticket' );
    }

    public function edit_ticket() {
        if( current_user_can( 'edit_tickets' ) ) {
            $user = wp_get_current_user();
            $ticket = $this->validate_request( $user );

            if( $ticket !== false ) {

                update_user_meta( $user->ID, 'current_ticket', is_null( $ticket ) ? '' : $ticket->ID );

                $this->send_edit_form( $ticket );
            } else {
                wp_send_json_error( __( 'Permission error', TEXT_DOMAIN ) );
            }
        }
    }

    public function save_ticket() {
        $user = wp_get_current_user();
        $ticket = $this->validate_request( $user );
        $error = false;

        if( $ticket !== false ) {
            $form = $this->form_builder->configure( current_user_can( 'edit_ticket_meta' ) );

            if( $form->is_valid() ) {
                $data = $form->get_data();
                $ticket_id = null;

                if( isset( $ticket ) && $ticket->ID == get_user_meta( $user->ID, 'current_ticket', true ) ) {
                    $ticket_id = $ticket->ID;
                }

                $post_id = wp_insert_post( [
                    'ID'            => $ticket_id,
                    'post_title'    => $data['title'],
                    'post_content'  => $data['content'],
                    'post_status'   => 'publish',
                    'post_type'     => 'support_ticket',

                    'post_author'    => isset( $ticket ) ? $ticket->post_author : null,
                    'comment_status' => 'open'
                ] );

                if( $post_id > 0 ) {
                    if( current_user_can( 'edit_ticket_meta' ) ) {
                        update_post_meta( $post_id, 'email', $data['email'] );
                        update_post_meta( $post_id, 'agent', $data['agent'] );
                        update_post_meta( $post_id, 'status', $data['status'] );
                        update_post_meta( $post_id, 'date_opened', $data['date_opened'] );
                    } else {
                        update_post_meta( $post_id, 'email', $user->user_email );
                        update_post_meta( $post_id, 'date_opened', date( 'Y-m-d' ) );
                    }
                } else {
                    $error = __( 'An error has occurred', TEXT_DOMAIN );
                }
            } else {
                $error = $form->get_errors();
            }
        } else {
            $error = __( 'Permission error', TEXT_DOMAIN );
        }

        if( !empty( $error ) ) {
            wp_send_json_error( $error );
        }
    }

    private function validate_request( $user ) {
        $ticket = null;

        if( isset( $_REQUEST['ticket_id'] ) ) {
            $post = get_post( $_REQUEST['ticket_id'] );

            if ( isset( $post ) && $post->post_type == 'support_ticket' ) {
                if( $post->post_author == $user->ID || current_user_can( 'edit_others_tickets' ) ) {
                    $ticket = $post;
                } else {
                    $ticket = false;
                }
            }
        }

        return $ticket;
    }

    private function send_edit_form( $post ) {
        $form = $this->form_builder->configure( current_user_can( 'edit_ticket_meta' ), $post );

        wp_send_json( [
            'success' => true,
            'html' => $this->view->render( 'edit_ticket',
                [
                    'form'          => $form,
                    'post'          => $post,
                    'ajax_action'   => 'save_support_ticket',
                ]
            )
        ] );
    }

//
    public function render_dash() {
        echo $this->view->render( 'dash' );
    }
}
