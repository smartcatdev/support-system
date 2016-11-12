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

        $this->add_ajax_action( 'support_comment_edit', 'comment_form' );
        $this->add_ajax_action( 'support_ticket_reply', 'submit_comment' );
        $this->add_ajax_action( 'support_ticket_comments', 'ticket_comments' );
    }

    public function comment_form() {

        if( isset( $_REQUEST['comment_id'] ) ) {
            $comment = get_comment( $_REQUEST['comment_id'] );

            if( !empty( $comment ) && wp_get_current_user()->ID == $comment->user_id ) {

                wp_send_json_success( $this->view->render( 'comment_form',
                    [
                        'comment_action' => 'support_comment_edit',
                        'form'  => $this->configure_comment_form( null, $comment ),
                        'submit_text' => [
                            'default' => 'Save',
                            'success' => 'Saved',
                            'fail' => 'Error',
                            'wait' => 'Saving'
                        ]
                    ]
                ) );
            }
        }
    }

    public function submit_comment() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            $form = $this->configure_comment_form( $ticket );

            if( $form->is_valid() ) {
                $data = $form->get_data();
                $user = wp_get_current_user();

                //TODO add error for flooding
                add_filter( 'comment_flood_filter', '__return_false' );

                $comment = wp_handle_comment_submission( [
                    'comment_post_ID'      => $data['ticket_id'],
                    'author'               => $user->display_name,
                    'email'                => $user->user_email,
                    'url'                  => $user->user_url,
                    'comment'              => $data['comment_content'],
                    'comment_parent'       => 0,
                    'user_id'              => $user->ID,
                    '_wp_unfiltered_html_comment' => '_wp_unfiltered_html_comment'
                ] );

                if( !is_wp_error( $comment ) ) {
                    wp_send_json( [
                        'success' => true,
                        'data'    => $this->view->render( 'comment', [
                            'comment' => $comment
                        ] )
                    ] );
                }
            } else {
                wp_send_json_error( $form ->get_errors() );
            }
        }

    }

    public function ticket_comments() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            wp_send_json_success( $this->view->render( 'comment_section',
                [
                    'comment_form' => $this->configure_comment_form( $ticket ),
                    'comment_action' => 'support_ticket_reply',
                    'comments' => get_comments( [ 'post_id' => $ticket->ID, 'order' => 'ASC' ] )
                ]
            ) );
        } else {
            wp_send_json_error( __( 'You don\'t have permission to view comments for this ticket.', TEXT_DOMAIN ) );
        }
    }

    private function configure_comment_form( $post, $comment = false ) {
        $this->builder->clear_config();

        $this->builder->add( TextArea::class, 'comment_content',
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
            $this->builder->add( Hidden::class, 'ticket_id',
                [
                    'value' => $post->ID
                ]
            )->get_form();
        }


        return $this->builder->get_form();
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

}