<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\TemplateUtils;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

class CommentComponent extends AbstractComponent {

    public function update_comment() {
        $comment = $this->get_comment( $_REQUEST['comment_id'] );

        if( !empty( $comment ) && TicketUtils::comments_enabled( $comment->comment_post_ID ) ) {
            $ticket = $this->get_ticket( $comment->comment_post_ID );

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
                            'comment' => get_comment( $comment->comment_ID ),
                            'comments_enabled' => TicketUtils::comments_enabled( $ticket->ID )
                        )
                    )
                );
            } else {
                wp_send_json_error( __( 'Reply cannot be blank', Plugin::ID ), 400 );
            }
        }
    }

    public function submit_comment() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        if( !empty( $ticket ) && TicketUtils::comments_enabled( $ticket->ID ) && !empty( $_REQUEST['content'] ) ) {
            $user = wp_get_current_user();
            $status = get_post_meta( $ticket->ID, 'status', true );

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

            if( !is_wp_error( $comment ) ) {
                if( current_user_can( 'edit_others_tickets' ) ) {
                    update_post_meta( $ticket->ID, 'status', 'waiting' );

                    add_filter( 'parse_email_template', function( $content ) use ( $comment, $ticket ) {
                        return str_replace(
                            array( '{%agent%}', '{%reply%}', '{%subject%}' ),
                            array( $comment->comment_author, $comment->comment_content, $ticket->post_title ),
                            $content
                        );
                    } );

                    Mailer::send_template( get_option( Option::REPLY_EMAIL_TEMPLATE ), get_post_meta( $ticket->ID, 'email', true ) );
                } elseif( $status != 'new' ) {
                    update_post_meta( $ticket->ID, 'status', 'responded' );
                }

                wp_send_json_success(
                    TemplateUtils::render_template(
                        $this->plugin->template_dir . '/comment.php',
                        array(
                            'comment' => $comment,
                            'comments_enabled' => TicketUtils::comments_enabled( $ticket->ID )
                        )
                ), 201 );
            }
        } else {
            wp_send_json_error( __( 'Reply cannot be blank', Plugin::ID ), 400 );
        }
    }

    public function delete_comment() {
        $comment = $this->get_comment( $_REQUEST['comment_id'] );

        if( !empty( $comment ) && TicketUtils::comments_enabled( $comment->comment_post_ID ) ) {
            if( wp_delete_comment( $comment->comment_ID, true ) ) {
                wp_send_json_success( null );
            } else {
                wp_send_json_error( null, 500 );
            }
        }
    }

    public function list_comments() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );
          
        if( !empty( $ticket ) ) {
            $comments = array();

            foreach( get_comments( array( 'post_id' => $ticket->ID, 'order' => 'ASC' ) ) as $comment ) {
                $comments[ $comment->comment_ID ] = TemplateUtils::render_template(
                    $this->plugin->template_dir . '/comment.php',
                    array(
                        'comment' => $comment,
                        'comments_enabled' => TicketUtils::comments_enabled( $ticket->ID )
                    ) );
            }

            wp_send_json_success( $comments );
        }
    }

    public function remove_feed_comments( $for_comments ) {
        if( $for_comments ) {

            $comments = $GLOBALS['wp_query']->comments;
            $num_comments = $GLOBALS['wp_query']->comment_count;

            for( $ctr = 0; $ctr < $num_comments; $ctr++ ) {

                $post = get_post( $comments[ $ctr ]->comment_post_ID );

                if ( $post && $post->post_type == 'support_ticket' ) {
                    unset( $GLOBALS['wp_query']->comments[ $ctr ] );
                    $GLOBALS['wp_query']->comment_count--;
                }
            }
        }
    }

    public function remove_widget_comments( $args ) {
        $args['post__not_in'] = $this->plugin->ticket_ids;

        return $args;
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_update_comment' => array( 'update_comment' ),
            'wp_ajax_support_submit_comment' => array( 'submit_comment' ),
            'wp_ajax_support_delete_comment' => array( 'delete_comment' ),
            'wp_ajax_support_list_comments' => array( 'list_comments' ),

            'widget_comments_args' => array( 'remove_widget_comments' ),
            'do_feed_rss2' => array( 'remove_feed_comments', 1 ),
            'do_feed_rss' => array( 'remove_feed_comments', 1 ),
            'do_feed_rdf' => array( 'remove_feed_comments', 1 ),
            'do_feed_atom' => array( 'remove_feed_comments', 1 ),
            'do_feed' => array( 'remove_feed_comments', 1 )
        );
    }

    private function get_ticket( $id ) {
        $args = array( 'p' => $id, 'post_type' => 'support_ticket' );

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['post_author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );
        $post = $query->post;

        return $post;
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
