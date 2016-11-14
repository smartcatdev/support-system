<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\form\constraint\Required;
use SmartcatSupport\form\field\Hidden;
use SmartcatSupport\form\field\TextArea;
use SmartcatSupport\form\FormBuilder;
use const SmartcatSupport\TEXT_DOMAIN;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\util\View;

class CommentHandler extends ActionListener {
    private $builder;
    private $view;

    public function __construct( View $view, FormBuilder $builder ) {
        $this->view = $view;
        $this->builder = $builder;

        $this->add_ajax_action( 'support_edit_comment', 'edit_comment' );
        $this->add_ajax_action( 'support_save_comment', 'save_comment' );
        $this->add_ajax_action( 'support_ticket_reply', 'submit_comment' );
        $this->add_ajax_action( 'support_delete_comment', 'delete_comment' );
        $this->add_ajax_action( 'support_ticket_comments', 'ticket_comments' );
    }

    public function edit_comment() {
        $comment = $this->validate_comment_request();

        if ( !empty( $comment ) ) {
            wp_send_json_success( $this->view->render( 'comment_form',
                [
                    'action'      => 'support_save_comment',
                    'after'       => 'refresh_comment',
                    'form'        => $this->configure_comment_form( null, $comment ),
                    'submit_text' => [
                        'default' => 'Save',
                        'success' => 'Saved',
                        'fail'    => 'Error',
                        'wait'    => 'Saving'
                    ]
                ]
            ) );
        }
    }

    public function save_comment() {
        $comment = $this->validate_comment_request();

        if ( !empty( $comment ) ) {
            $form = $this->configure_comment_form( null, $comment );

            if ( $form->is_valid() ) {
                $data = $form->get_data();

                wp_update_comment( [
                    'comment_ID'      => $data['id'],
                    'comment_content' => $data['content'],
                    'comment_date' =>  current_time( 'mysql' ),
                    'comment_date_gmt' =>  current_time( 'mysql', 1 )
                ] );

                wp_send_json_success( $this->view->render( 'comment', [
                    'comment' => get_comment( $data['id'] )
                ] ) );
            } else {
                wp_send_json_error( $form->get_errors() );
            }
        }
    }

    public function submit_comment() {
        $ticket = $this->valid_ticket_request();

        if( !empty( $ticket ) ) {
            $form = $this->configure_comment_form( $ticket );

            if( $form->is_valid() ) {
                $data = $form->get_data();
                $user = wp_get_current_user();

                //TODO add error for flooding
                add_filter( 'comment_flood_filter', '__return_false' );

                $comment = wp_handle_comment_submission( [
                    'comment_post_ID'      => $data['id'],
                    'author'               => $user->display_name,
                    'email'                => $user->user_email,
                    'url'                  => $user->user_url,
                    'comment'              => $data['content'],
                    'comment_parent'       => 0,
                    'user_id'              => $user->ID,
                    '_wp_unfiltered_html_comment' => '_wp_unfiltered_html_comment'
                ] );

                if( !is_wp_error( $comment ) ) {
                    wp_send_json_success( $this->view->render( 'comment', [
                            'comment' => $comment
                    ] ) );
                }
            } else {
                wp_send_json_error( $form ->get_errors() );
            }
        }
    }

    public function delete_comment() {
        $comment = $this->validate_comment_request();

        if( !empty( $comment ) ) {
            wp_delete_comment( $comment->comment_ID, true );
            wp_send_json_success();
        }
    }

    public function ticket_comments() {
        $ticket = $this->valid_ticket_request();

        if( !empty( $ticket ) ) {
            wp_send_json_success( $this->view->render( 'comment_section',
                [
                    'form' => $this->configure_comment_form( $ticket ),
                    'action' => 'support_ticket_reply',
                    'after' => 'append_comment',
                    'comments' => get_comments( [ 'post_id' => $ticket->ID, 'order' => 'ASC' ] ),
                    'submit_text' => [
                        'default' => 'Reply',
                        'success' => 'Sent',
                        'fail' => 'Error',
                        'wait' => 'Sending'
                    ]
                ]
            ) );
        } else {
            wp_send_json_error( __( 'You don\'t have permission to view comments for this ticket.', TEXT_DOMAIN ) );
        }
    }

    private function configure_comment_form( $post, $comment = false ) {
        $this->builder->clear_config();

        $this->builder->add( TextArea::class, 'content',
            [
                'rows' => 4,
                'value' => $comment ? $comment->comment_content : '',
                'error_msg' => __( 'Reply cannot be blank', TEXT_DOMAIN ),
                'constraints' => [
                    $this->builder->create_constraint( Required::class )
                ]
            ]
        );

        if( !empty( $post ) ) {
            $this->builder->add( Hidden::class, 'id', [ 'value' => $post->ID ] );
        } else if( !empty( $comment ) ) {
            $this->builder->add( Hidden::class, 'id', [ 'value' => $comment->comment_ID ] );
        }

        return $this->builder->get_form();
    }

    private function valid_ticket_request() {
        $ticket = null;
        $user = wp_get_current_user();

        if( user_can( $user->ID, 'edit_tickets' ) ) {
            if( isset( $_REQUEST['id'] ) && (int) $_REQUEST['id'] > 0 ) {
                $post = get_post( $_REQUEST['id'] );

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

    private function validate_comment_request() {
        $comment = null;

        if( isset( $_REQUEST['id'] ) ) {
            $result = get_comment( $_REQUEST['id'] );

            if ( !empty( $result ) && wp_get_current_user()->ID == $result->user_id ) {
                $comment = $result;
            }
        } else {
            $comment = false;
        }

        return $comment;
    }

}