<?php

namespace ucare;


// Update the comment status after a comment has been made
add_action( 'comment_post', 'ucare\comment_save' );
add_action( 'edit_comment', 'ucare\comment_save' );


function comment_save( $id ) {

    $post = get_post( get_comment( $id )->comment_post_ID );

    if( $post->post_type == 'support_ticket' ) {

        $status = get_post_meta( $post->ID, 'status', true );

        // Don't update the status if the ticket has already been closed
        if( $status != 'closed' ) {

            // If the user is an agent or admin
            if( current_user_can( 'manage_support_tickets' ) ) {

                update_post_meta( $post->ID, 'status', 'waiting' );

                // If the status is new, overwrite it to clear stale values else set status to responded
            } else {
                update_post_meta( $post->ID, 'status', $status == 'new' ? 'new' : 'responded' );
            }

        }

    }

}
