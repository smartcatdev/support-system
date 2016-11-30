<?php

namespace SmartcatSupport\ajax;

use SmartcatSupport\form\constraint\Required;
use SmartcatSupport\form\field\Hidden;
use SmartcatSupport\form\field\TextArea;
use SmartcatSupport\form\FormBuilder;
use function SmartcatSupport\render_template;
use const SmartcatSupport\TEXT_DOMAIN;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\util\TemplateRender;

class Comment extends ActionListener {
    private $builder;

    public function __construct( FormBuilder $builder ) {
        $this->builder = $builder;

        $this->add_ajax_action( 'support_update_comment', 'update_comment' );
        $this->add_ajax_action( 'support_submit_comment', 'submit_comment' );
        $this->add_ajax_action( 'support_delete_comment', 'delete_comment' );
        $this->add_ajax_action( 'support_ticket_comments', 'ticket_comments' );
    }

    public function update_comment() {
        $comment = $this->validate_comment_request();

        if ( !empty( $comment ) && !empty( $_REQUEST['content'] ) ) {
            wp_update_comment( array(
                'comment_ID'       => $comment->comment_ID,
                'comment_content'  => $_REQUEST['content'],
                'comment_date'     => current_time( 'mysql' ),
                'comment_date_gmt' => current_time( 'mysql', 1 )
            ) );

            wp_send_json_success(
                render_template( 'comment', array(
                    'comment' => get_comment( $comment->comment_ID )
                ) )
            );
        } else {
            wp_send_json_error( array( 'content' => __( 'Cannot be blank', TEXT_DOMAIN ) ) );
        }
    }

    public function submit_comment() {
        $ticket = $this->valid_ticket_request();

        if( !empty( $ticket ) && !empty( $_REQUEST['content'] ) ) {
            $user = wp_get_current_user();

            //TODO add error for flooding
            add_filter( 'comment_flood_filter', '__return_false' );

            $comment = wp_handle_comment_submission( [
                'comment_post_ID'             => $ticket->ID,
                'author'                      => $user->display_name,
                'email'                       => $user->user_email,
                'url'                         => $user->user_url,
                'comment'                     => $_REQUEST['content'],
                'comment_parent'              => 0,
                'user_id'                     => $user->ID,
                '_wp_unfiltered_html_comment' => '_wp_unfiltered_html_comment'
            ] );

            if ( !is_wp_error( $comment ) ) {
                wp_send_json_success(
                    render_template( 'comment', array(
                        'comment' => $comment
                    ) )
                );
            }
        } else {
            wp_send_json_error( array( 'content' => __( 'Reply cannot be blank', TEXT_DOMAIN ) ) );
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
            wp_send_json_success(
                render_template( 'comment_section',
                    array(
                        'post' => $ticket,
                        'comments' => get_comments( array( 'post_id' => $ticket->ID, 'order' => 'ASC' ) )
                    )
                )
            );
        }
    }

    private function valid_ticket_request() {
        $ticket = null;
        $user = wp_get_current_user();

        if( isset( $_REQUEST['id'] ) && (int) $_REQUEST['id'] > 0 ) {
            $post = get_post( $_REQUEST['id'] );

            if ( isset( $post ) ) {
                if ( $post->post_type == 'support_ticket' &&
                     ( $post->post_author == $user->ID || user_can( $user->ID, 'edit_others_tickets' ) )
                ) {
                    $ticket = $post;
                }
            }
        } else {
            $ticket = false;
        }

        return $ticket;
    }

    private function validate_comment_request() {
        $comment = null;

        if( isset( $_REQUEST['comment_id'] ) ) {
            $result = get_comment( $_REQUEST['comment_id'] );

            if ( !empty( $result ) && wp_get_current_user()->ID == $result->user_id ) {
                $comment = $result;
            }
        } else {
            $comment = false;
        }

        return $comment;
    }
}
