<?php

namespace SmartcatSupport\ajax;


class Media extends AjaxComponent {

    public function upload_media() {
        $result = media_handle_upload( 'file', 0 );

        if( !is_wp_error( $result ) ) {
            wp_send_json_success( array( 'id' => $result ), 200 );
        } else {
            wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
        }
    }

    public function delete_media() {
        $post = get_post( $_REQUEST['attachment_id'] );

        if( $post->post_author == wp_get_current_user()->ID ) {
            if( wp_delete_attachment( $post->ID, true ) ) {
                wp_send_json_success( array( 'message' => __( 'Attachment successfully removed', \SmartcatSupport\PLUGIN_ID ) ) );
            } else {
                wp_send_json_success( array( 'message' => __( 'Error occurred when removing attachment', \SmartcatSupport\PLUGIN_ID ) ), 500 );
            }
        }
    }

    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'wp_ajax_support_upload_media' => array( 'upload_media' ),
            'wp_ajax_support_delete_media' => array( 'delete_media' )
        ) );
    }
}