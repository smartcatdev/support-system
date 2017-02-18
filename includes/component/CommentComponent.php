<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

class CommentComponent extends AbstractComponent {

    /**
     * AJAX action to update a comment on a ticket.
     *
     * @uses $_POST['comment_id'] The ID of the comment to be updated.
     * @uses $_POST['content'] The new content of the comment.
     * @since 1.0.0
     */
    public function update_comment() {
        $comment = $this->get_comment( $_POST['comment_id'] );

        if( !empty( $comment ) ) {
            $ticket = $this->get_ticket( $comment->comment_post_ID );

            if ( !empty( $ticket ) && !empty( $_POST['content'] ) ) {
                $result = wp_update_comment( array(
                    'comment_ID'       => $comment->comment_ID,
                    'comment_content'  => $_POST['content'],
                    'comment_date'     => current_time( 'mysql' ),
                    'comment_date_gmt' => current_time( 'mysql', 1 )
                ) );

                if( $result ) {
                    $comment = $this->get_comment( $comment->comment_ID );

                    wp_send_json_success( include_once $this->plugin->template_dir . '/comment.php' );
                }
            } else {
                wp_send_json_error( __( 'Reply cannot be blank', Plugin::ID ), 400 );
            }
        }
    }

    /**
     * AJAX action for submitting new tickets. Ensure user has proper privileges and then emails
     * the comments content. If the user is an agent, sets the status to waiting, else sets the
     * status to responded.
     *
     * @uses $_POST['id'] The id of the ticket to comment on.
     * @uses $_POST['content'] The content of the comment.
     * @since 1.0.0
     */
    public function submit_comment() {
        $ticket = $this->get_ticket( $_POST['id'] );

        if ( !empty( $ticket ) && !empty( $_POST['content'] ) ) {
            $user   = wp_get_current_user();
            $status = get_post_meta( $ticket->ID, 'status', true );

            //TODO add error for flooding
            add_filter( 'comment_flood_filter', '__return_false' );

            $comment = wp_handle_comment_submission( array(
                'comment_post_ID'             => $ticket->ID,
                'author'                      => $user->display_name,
                'email'                       => $user->user_email,
                'url'                         => $user->user_url,
                'comment'                     => $_POST['content'],
                'comment_parent'              => 0,
                'user_id'                     => $user->ID,
                '_wp_unfiltered_html_comment' => '_wp_unfiltered_html_comment'
            ) );

            if ( !is_wp_error( $comment ) ) {
                if ( current_user_can( 'edit_others_tickets' ) ) {
                    update_post_meta( $ticket->ID, 'status', 'waiting' );

                    // Grab email template vars
                    add_filter( 'parse_email_template', function ( $content ) use ( $comment, $ticket ) {
                        return str_replace(
                            array( '{%agent%}', '{%reply%}', '{%subject%}' ),
                            array( $comment->comment_author, $comment->comment_content, $ticket->post_title ),
                            $content
                        );
                    } );

                    Mailer::send_template( get_option( Option::REPLY_EMAIL_TEMPLATE ), get_post_meta( $ticket->ID, 'email', true ) );
                } elseif ( $status != 'new' ) {
                    update_post_meta( $ticket->ID, 'status', 'responded' );
                }

                wp_send_json(
                    array(
                        'success' => true,
                        'data'   => include_once $this->plugin->template_dir . '/comment.php',
                        'ticket' => $ticket->ID
                    ), 201 );
            } else {
                wp_send_json_error( __( 'Reply cannot be blank', Plugin::ID ), 400 );
            }
        }
    }

    /**
     * AJAX action to delete a comment. Ensures user has proper privilege.
     *
     * @uses $_REQUEST['comment_id'] The ID of the comment to delete.
     * @since 1.0.0
     */
    public function delete_comment() {
        $comment = $this->get_comment( $_REQUEST['comment_id'] );

        if( !empty( $comment ) ) {
            if( wp_delete_comment( $comment->comment_ID, true ) ) {
                wp_send_json_success( null );
            } else {
                wp_send_json_error( null, 500 );
            }
        }
    }

    /**
     * AJAX action to retrieve all comments for a ticket. Returns an array of rendered comments.
     *
     * @uses $_GET['id'] The ID of the ticket to retrieve comments for.
     * @since 1.0.0
     */
    public function list_comments() {
        $ticket = $this->get_ticket( $_GET['id'] );
          
        if( !empty( $ticket ) ) {
            $comments = array();

            foreach( get_comments( array( 'post_id' => $ticket->ID, 'order' => 'ASC' ) ) as $comment ) {
                $comments[ $comment->comment_ID ] = include $this->plugin->template_dir . '/comment.php';
            }

            wp_send_json_success( $comments );
        }
    }

    /**
     * Hack to remove all comments on support tickets from feeds.
     *
     * @param $for_comments
     * @since 1.0.0
     */
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

    /**
     * Hack to remove all ticket comments from recent comments widget.
     *
     * @param $args
     * @return mixed $args
     * @since 1.0.0
     */
    public function remove_widget_comments( $args ) {
        $args['post__not_in'] = $this->plugin->ticket_ids;

        return $args;
    }

    /**
     * Hooks that the Component is subscribed to.
     *
     * @see \smartcat\core\AbstractComponent
     * @see \smartcat\core\HookSubscriber
     * @return array $hooks
     * @since 1.0.0
     */
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

    /**
     * Gets a ticket.
     *
     * @param $id The ID of the ticket.
     * @return null || \WP_Post
     * @since 1.0.0
     */
    private function get_ticket( $id ) {
        $args = array( 'p' => $id, 'post_type' => 'support_ticket' );

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['post_author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );
        $post = $query->post;

        return $post;
    }

    /**
     * Gets a comment
     *
     * @param $id The ID of the comment.
     * @return null || \WP_Comment
     * @since 1.0.0
     */
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
