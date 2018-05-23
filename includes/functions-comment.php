<?php
/**
 * Functions for managing support ticket comments.
 *
 * @since 1.5.0
 * @package ucare
 */
namespace ucare;


// Update the comment status after a comment has been made
add_action( 'comment_post', 'ucare\comment_save' );
add_action( 'edit_comment', 'ucare\comment_save' );

// Remove comments from public feeds
add_filter( 'comment_feed_where', 'ucare\remove_feed_comments' );

// Remove front-end widget comments
add_filter( 'widget_comments_args', 'ucare\remove_widget_comments' );

// Remove Admin dashboard comments
add_filter('comments_clauses', 'ucare\remove_admin_comments' );


/**
 * Update the status of a post when a comment is saved.
 *
 * @action comment_post
 * @action edit_comment
 *
 * @param $id
 *
 * @since 1.4.2
 * @return void
 */
function comment_save( $id ) {
    $post = get_post( get_comment( $id )->comment_post_ID );

    if ( $post->post_type == 'support_ticket' ) {
        $status = get_post_meta( $post->ID, 'status', true );

        if ( $status != 'closed' ) {

            if ( current_user_can( 'manage_support_tickets' ) ) {
                update_post_meta( $post->ID, 'status', 'waiting' );

            // If the status is new, overwrite it to clear stale values else set status to responded
            } else {
                update_post_meta( $post->ID, 'status', $status == 'new' ? 'new' : 'responded' );
            }
        }
    }

}


/**
 * Remove comments from feeds.
 *
 * @filter comment_feed_where
 *
 * @global $wpdb
 *
 * @param $where
 *
 * @since 1.6.0
 * @return string
 */
function remove_feed_comments( $where ) {
    global $wpdb;
    return $where . " AND $wpdb->posts.post_type NOT IN ( 'support_ticket' )";
}


/**
 * Remove comments from comment widget.
 *
 * @filter widget_comments_args
 *
 * @param array $args
 *
 * @since 1.6.0
 * @return mixed
 */
function remove_widget_comments( $args ) {
    $args['post_type'] = array( 'post', 'page' );
    return $args;
}


/**
 * Remove comments from admin dashboard widget.
 *
 * @filter comments_clauses
 *
 * @global $wpdb
 * @global $screen
 *
 * @param array $query
 *
 * @since 1.6.0
 * @return array
 */
function remove_admin_comments( $query ) {
    global $wpdb, $screen;

    if ( is_admin() && pluck( $screen, 'id' ) == 'dashboard' ) {
        $query['join']  .= "INNER JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID";
        $query['where'] .=  " AND $wpdb->posts.post_type NOT IN ( 'support_ticket' )";
    }

    return $query;
}