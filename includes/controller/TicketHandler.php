<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\form\constraint\Match;
use SmartcatSupport\form\field\TextEditor;
use SmartcatSupport\util\View;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\field\TextArea;
use SmartcatSupport\form\field\Hidden;
use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\constraint\Date;
use SmartcatSupport\form\constraint\Required;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class TicketHandler extends ActionListener {
    private $view;
    private $builder;

    public function __construct( View $view, FormBuilder $builder ) {
        $this->view = $view;
        $this->builder = $builder;

        $this->add_ajax_action( 'edit_support_ticket', 'edit_ticket' );
        $this->add_ajax_action( 'save_support_ticket', 'save_ticket' );
    }

    public function edit_ticket() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            $this->ticket_detail( $ticket );
        } else {
            wp_send_json_error( __( 'You don\'t have permission to edit this ticket.', TEXT_DOMAIN ) );
        }
    }

    public function save_ticket() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            $form = $this->configure_editor_form( $ticket );
            $meta = $this->configure_meta_form( $ticket );

            if( $form->is_valid() && $meta->is_valid() ) {
                $data = $form->get_data();

                $post_id = wp_insert_post( [
                    'ID'            => $ticket->ID,
                    'post_title'    => $data['subject'],
                    'post_content'  => $data['content'],
                    'post_status'   => 'publish',
                    'post_type'     => 'support_ticket',
                    'post_author'    => null,
                    'comment_status' => 'open'
                ] );

                if( !empty( $post_id ) ) {
                    foreach( $meta->get_data() as $field => $value ) {
                        update_post_meta( $post_id, $field, $value );
                    }

                    update_post_meta( $post_id, '_edit_last', wp_get_current_user()->ID );
                    wp_send_json_success( $this->ticket_detail( get_post( $post_id ) ) );
                }


            } else {
                wp_send_json_error( $form->get_errors() + $meta->get_errors() );
            }
        }
    }

    private function valid_request() {
        $ticket = null;
        $user = wp_get_current_user();

        if( user_can( $user->ID, 'edit_tickets' ) ) {
            if( isset( $_REQUEST['ticket_id'] ) && (int) $_REQUEST['ticket_id'] > 0 ) {
                $post = get_post( $_REQUEST['ticket_id'] );

                if( isset( $post ) )
                    if( $post->post_type == 'support_ticket' &&
                        ( $post->post_author == $user->ID || user_can( $user->ID, 'edit_others_tickets' ) ) ) {
                        $ticket = $post;
                    }
            } else {
                $ticket = false;
            }
        }

        return $ticket;
    }

    private function ticket_detail( $post ) {
        wp_send_json_success(
            $this->view->render( 'ticket',
                [
                    'post'           => $post,
                    'editor_form'    => $this->configure_editor_form( $post ),
                    'meta_form'      => $this->configure_meta_form( $post ),
                    'ticket_action'  => 'save_support_ticket',
                ]
        ) );
    }

    private function configure_editor_form( $post ) {
        $this->builder->clear_config();

        $this->builder->add( Hidden::class, 'ticket_id',
            [
                'value'       => $post->ID,
//                'constraints' =>  [
//                    $this->builder->create_constraint( Match::class, $post->ID )
//                ]
            ]
        )->add( TextBox::class, 'subject',
            [
                'value'         => isset( $post ) ? $post->post_title : '',
                'error_msg'     => __( 'Subject cannot be blank', TEXT_DOMAIN ),
                'constraints'   =>  [
                    $this->builder->create_constraint( Required::class )
                ]
            ]
        )->add( TextArea::class, 'content',
            [
                'value' => isset( $post ) ? $post->post_content : '',
                'error_msg' => __( 'Description cannot be blank', TEXT_DOMAIN ),
                'constraints' =>  [
                    $this->builder->create_constraint( Required::class )
                ]
            ]
        );

        return $this->builder->get_form();
    }

    private function configure_meta_form( $post ) {
        $this->builder->clear_config();

        $agents = [ '' => __( 'No Agent Assigned', TEXT_DOMAIN ) ] + support_system_agents();
        $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );

        $this->builder->add( TextBox::class, 'email',
            [
                'type'              => 'email',
                'label'             => 'Contact Email',
                'value'             => get_post_meta( $post->ID, 'email', true ),
                'sanitize_callback' => 'sanitize_email'
            ]
        )->add( SelectBox::class, 'agent',
            [
                'error_msg'   => __( 'Invalid agent selected', TEXT_DOMAIN ),
                'label'       => 'Assigned To',
                'options'     => $agents,
                'value'       => get_post_meta( $post->ID, 'agent', true ),
                'constraints' => [
                    $this->builder->create_constraint( Choice::class, array_keys( $agents ) )
                ]
            ]
        )->add( SelectBox::class, 'status',
            [
                'error_msg'   => __( 'Invalid status selected', TEXT_DOMAIN ),
                'label'       => 'Status',
                'options'     => $statuses,
                'value'       => get_post_meta( $post->ID, 'status', true ),
                'constraints' => [
                    $this->builder->create_constraint( Choice::class, array_keys( $statuses ) )
                ]
            ]
        );

        return apply_filters( 'support_ticket_meta_form', $this->builder, $post )->get_form();
    }

    //TODO Put this in a dash handler class for dashboard events
    public function render_dash() {
        echo $this->view->render( 'dash' );
    }
}
