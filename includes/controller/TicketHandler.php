<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\form\constraint\Match;
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

    public function edit_ticket() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            $this->ticket_detail( $ticket );
        } else {
            wp_send_json_error( __( 'You don\'t have permission to edit this ticket.', TEXT_DOMAIN ) );
        }
    }

    private function ticket_detail( $post, $comments = true ) {
        $args = [
            'post'           => $post,
            'editor_form'    => $this->configure_editor_form( $post ),
            'ticket_action'  => 'save_support_ticket',
        ];

        if( $comments ) {
            $args['comment_form'] = $this->configure_comment_form( $post );
            $args['comment_action'] = 'support_ticket_reply';
            $args['comments'] = $this->get_comments( $post->ID );
        }

        wp_send_json( [
            'success' => true,
            'html' => $this->view->render( 'ticket', $args )
        ] );
    }

    public function save_ticket() {
        $ticket = $this->valid_request();
        $error = false;

        if( !empty( $ticket ) ) {
            $form = $this->configure_editor_form( $ticket );

            if( $form->is_valid() ) {
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
                    foreach( $data as $field => $value ) {
                        $count = 0;
                        $field = str_replace( 'm__', '', $field, $count );

                        if( $count > 0 ) {
                            update_post_meta( $post_id, $field, $value );
                        }
                    }
                }
            } else {
                $error = $form->get_errors();
            }
        }

        if( !empty( $error ) ) {
            wp_send_json_error( $error );
        } else {
            wp_send_json_success( __( 'Ticket saved', TEXT_DOMAIN ) );
        }
    }

    public function submit_comment() {
        // get post from user meta

        $user = wp_get_current_user();

        $comment_id = wp_new_comment( [
            'comment_post_ID'       => $post->ID,
            'comment_author'        => $user->display_name,
            'comment_author_email'  => $user->user_email,
            'comment_content'       => $comment,
            'comment_parent'        => 0,
            'user_id'               => $user->ID

        ] );


    }

    private function configure_editor_form( $post ) {
        $this->builder->add( Hidden::class, 'ticket_id',
            [
                'value'       => $post->ID,
//                'constraints' =>  [
//                    $this->builder->create_constraint( Match::class, $post->ID )
//                ]
            ]
        )->add( TextBox::class, 'subject',
            [
                'label'         => 'Subject',
                'value'         => isset( $post ) ? $post->post_title : '',
                'error_msg'     => __( 'Subject cannot be blank', TEXT_DOMAIN ),
                'constraints'   =>  [
                    $this->builder->create_constraint( Required::class )
                ]
            ]
        )->add( TextArea::class, 'content',
            [
                'label' => 'Description',
                'value' => isset( $post ) ? $post->post_content : '',
                'error_msg' => __( 'Description cannot be blank', TEXT_DOMAIN ),
                'constraints' =>  [
                    $this->builder->create_constraint( Required::class )
                ]
            ]
        );

        if( current_user_can( 'edit_ticket_meta' ) ) {

            $agents = [ '' => __( 'No Agent Assigned', TEXT_DOMAIN ) ] + support_system_agents();
            $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
            $date = get_post_meta( $post->ID, 'date_opened', true );

            $this->builder->add( TextBox::class, 'm__email',
                [
                    'type'              => 'email',
                    'label'             => 'Contact Email',
                    'value'             => get_post_meta( $post->ID, 'email', true ),
                    'sanitize_callback' => 'sanitize_email'
                ]
            )->add( SelectBox::class, 'm__agent',
                [
                    'label'       => 'Assigned To',
                    'options'     => $agents,
                    'value'       => get_post_meta( $post->ID, 'agent', true ),
                    'constraints' => [
                        $this->builder->create_constraint( Choice::class, array_keys( $agents ) )
                    ]
                ]
            )->add( SelectBox::class, 'm__status',
                [
                    'label'       => 'Status',
                    'options'     => $statuses,
                    'value'       => get_post_meta( $post->ID, 'status', true ),
                    'constraints' => [
                        $this->builder->create_constraint( Choice::class, array_keys( $statuses ) )
                    ]
                ]
            )->add( TextBox::class, 'm__date_opened',
                [
                    'label'       => 'Date Opened',
                    'type'        => 'date',
                    'value'       => $date == '' ? date( 'Y-m-d' ) : $date,
                    'constraints' => [
                        $this->builder->create_constraint( Date::class )
                    ]
                ]
            );

        }

        return apply_filters( 'support_ticket_editor_form', $this->builder, $post )->get_form();
    }

    private function configure_comment_form( $post ) {
        $this->builder->clear_config();

        return $this->builder->add( TextArea::class, 'comment_content',
            [
                'error_msg' => __( 'Comment cannot be blank', TEXT_DOMAIN ),
                'constraints' =>  [
                    $this->builder->create_constraint( Required::class )
                ]
            ]
        )->add( Hidden::class, 'ticket_id',
            [
//                'constraints' =>  [
//                    $this->builder->create_constraint( Match::class, $post->ID )
//                ]
            ]
        )->get_form();
    }

    private function get_comments( $post_id ) {
        return ( new \WP_Comment_Query() )->query( [ 'post_id' => $post_id ] );
    }




//
    public function render_dash() {
        echo $this->view->render( 'dash' );
    }
}
