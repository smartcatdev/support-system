<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use SmartcatSupport\util\TemplateUtils;
use const SmartcatSupport\PLUGIN_ID;

class CommentComponent extends AbstractComponent {

    public function update_comment() {
        $comment = $this->get_comment( $_REQUEST['comment_id'] );

        if( !empty( $comment ) ) {
            if ( ! empty( $_REQUEST['content'] ) ) {
                wp_update_comment( array(
                    'comment_ID'       => $comment->comment_ID,
                    'comment_content'  => $_REQUEST['content'],
                    'comment_date'     => current_time( 'mysql' ),
                    'comment_date_gmt' => current_time( 'mysql', 1 )
                ) );

                wp_send_json_success(
                    TemplateUtils::render_template(
                        $this->plugin->template_dir . '/comment.php',
                        array(
                            'comment' => get_comment( $comment->comment_ID )
                        )
                    )
                );
            } else {
                wp_send_json_error( array( 'content' => __( 'Cannot be blank', PLUGIN_ID ) ) );
            }
        }
    }

    public function submit_comment() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        if( !empty( $ticket ) && !empty( $_REQUEST['content'] ) ) {

            $response = array( 'success' => true, 'ticket_updated' => false );
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

            if( current_user_can( 'edit_others_tickets' ) ) {
                $status = get_post_meta( $ticket->ID, 'status', true );

                if( $status == 'new' || $status == 'viewed' ) {
                    update_post_meta( $ticket->ID, 'status', 'in_progress' );

                    $response['ticket'] = TemplateUtils::render_template(
                        $this->plugin->template_dir . '/ticket.php', array( 'ticket' => $ticket )
                    );

                    $response['ticket_updated'] = true;
                    $response['ticket_id'] = $ticket->ID;
                }
            }

            if ( !is_wp_error( $comment ) ) {

                $response['comment'] = TemplateUtils::render_template(
                    $this->plugin->template_dir . '/comment.php', array( 'comment' => $comment )
                );

                wp_send_json( $response );
            }
        } else {
            wp_send_json_error( array( 'content' => __( 'Reply cannot be blank', PLUGIN_ID ) ) );
        }
    }

    public function delete_comment() {
        $comment = $this->get_comment( $_REQUEST['comment_id'] );

        if( !empty( $comment ) ) {
            wp_send_json( array( 'success' => wp_delete_comment( $comment->comment_ID, true ) ) );
        }
    }

    public function list_comments() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );
          
        if( !empty( $ticket ) ) {
            wp_send_json_success(
                TemplateUtils::render_template(
                    $this->plugin->template_dir . '/comment_section.php',
                    array(
                        'post' => $ticket,
                        'comments' => get_comments( array( 'post_id' => $ticket->ID, 'order' => 'ASC' ) )
                    )
                )
            );
        }
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_update_comment' => array( 'update_comment' ),
            'wp_ajax_support_submit_comment' => array( 'submit_comment' ),
            'wp_ajax_support_delete_comment' => array( 'delete_comment' ),
            'wp_ajax_support_ticket_comments' => array( 'list_comments' )
        );
    }

    private function get_ticket( $id ) {
        $args = array( 'p' => $id, 'post_type' => 'support_ticket' );

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['post_author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );

        return $query->post;
    }

    private function get_comment( $id ) {
        $comment = null;
        $query = new \WP_Comment_Query( array(
            'comment__in' => array( $id ),
            'user_id' => wp_get_current_user()->ID )
        );

        if( !empty( $query->comments ) ) {
            $comment = $query->comments[0];
        }

        return $comment;
    }
}
