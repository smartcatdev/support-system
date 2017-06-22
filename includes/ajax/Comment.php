<?php

namespace ucare\ajax;

class Comment extends AjaxComponent {

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
            if ( !empty( $_POST['content'] ) ) {
                $result = wp_update_comment( array(
                    'comment_ID'       => $comment->comment_ID,
                    'comment_content'  => \ucare\util\encode_code_blocks( trim( $_POST['content'] ) ),
                    'comment_date'     => current_time( 'mysql' ),
                    'comment_date_gmt' => current_time( 'mysql', 1 )
                ) );

                $html = $this->render( $this->plugin->template_dir . '/comment.php',
                    array( 'comment' => $this->get_comment( $comment->comment_ID ) )
                );

                if( $result ) {
                    wp_send_json_success( $html );
                }
            } else {
                wp_send_json_error( __( 'Reply cannot be blank', 'ucare' ), 400 );
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
     * Hooks that the Component is subscribed to.
     *
     * @see \smartcat\core\AbstractComponent
     * @see \smartcat\core\HookSubscriber
     * @return array $hooks
     * @since 1.0.0
     */
    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'wp_ajax_support_update_comment' => array( 'update_comment' ),
            'wp_ajax_support_delete_comment' => array( 'delete_comment' ),
        ) );
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
