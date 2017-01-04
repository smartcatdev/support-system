<?php

namespace SmartcatSupport\ajax;

use function SmartcatSupport\render_template;
use const SmartcatSupport\PLUGIN_ID;

final class CommentActions {
    private function __construct() {}

    public static function init() {
        add_action( 'wp_ajax_support_update_comment', array( __CLASS__, 'update_comment' ) );
        add_action( 'wp_ajax_support_submit_comment', array( __CLASS__, 'submit_comment' ) );
        add_action( 'wp_ajax_support_delete_comment', array( __CLASS__, 'delete_comment' ) );
        add_action( 'wp_ajax_support_ticket_comments', array( __CLASS__, 'ticket_comments' ) );
    }

    public static function update_comment() {
        $comment = self::validate_comment_request();

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
            wp_send_json_error( array( 'content' => __( 'Cannot be blank', PLUGIN_ID ) ) );
        }
    }

    public static function submit_comment() {
        $ticket = self::valid_ticket_request();

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
            wp_send_json_error( array( 'content' => __( 'Reply cannot be blank', PLUGIN_ID ) ) );
        }
    }

    public static function delete_comment() {
        $comment = self::validate_comment_request();

        if( !empty( $comment ) ) {
            wp_delete_comment( $comment->comment_ID, true );
            wp_send_json_success();
        }
    }

    public static function ticket_comments() {
        $ticket = self::valid_ticket_request();

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

    private static function valid_ticket_request() {
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

    private static function validate_comment_request() {
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
