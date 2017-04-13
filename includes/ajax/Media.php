<?php

namespace SmartcatSupport\ajax;


class Media extends AjaxComponent {

    public function upload_media() {
        define( 'USE_SUPPORT_UPLOADS', true );

        $result = media_handle_upload( 'file', isset( $_REQUEST['ticket_id'] ) ? $_REQUEST['ticket_id'] : 0 );

        if( !is_wp_error( $result ) ) {
            wp_update_post( array( 'ID' => $result ) );
            wp_send_json_success( array( 'id' => $result ), 200 );
        } else {
            wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
        }
    }

    public function delete_media() {
        define( 'USE_SUPPORT_UPLOADS', true );

        if( isset( $_REQUEST['attachment_id'] ) ) {
            $post = get_post( $_REQUEST['attachment_id'] );

            if( $post->post_author == wp_get_current_user()->ID ) {
                if( wp_delete_attachment( $post->ID, true ) ) {
                    wp_send_json_success( array( 'message' => __( 'Attachment successfully removed', \SmartcatSupport\PLUGIN_ID ) ) );
                } else {
                    wp_send_json_success( array( 'message' => __( 'Error occurred when removing attachment', \SmartcatSupport\PLUGIN_ID ) ), 500 );
                }
            }
        }
    }

    public function media_dir( $uploads ) {
        if( defined( 'USE_SUPPORT_UPLOADS' ) ) {

            $user = wp_get_current_user();
            $dir = $uploads['basedir'];
            $url = $uploads['baseurl'];

            return array(
                'path'    => $dir . '/support_uploads/' . $user->ID,
                'url'     => $url . '/support_uploads/' . $user->ID,
                'subdir'  => '',
                'basedir' => $dir,
                'baseurl' => $url,
                'error'   => false,
            );

        } else {
            return $uploads;
        }
    }

    public function generate_filename( $file ) {
        if( defined( 'USE_SUPPORT_UPLOADS' ) ) {
            $ext = substr($file['name'], strrpos($file['name'], '.'));
            $file['name'] = wp_generate_uuid4() . $ext;
        }

        return $file;
    }

    public function restrict_uploads() {
        if( get_post_type() == 'attachment' ) {
            $post = get_post();
            $parent = get_post( $post->post_parent );

            if( $parent->post_type == 'support_ticket' ) {
                wp_safe_redirect( home_url(), 301 );
            } else {
                return;
            }
        }
    }

    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'upload_dir' => array( 'media_dir' ),
            'template_redirect' => array( 'restrict_uploads' ),
            'wp_handle_upload_prefilter' => array( 'generate_filename' ),
            'wp_ajax_support_upload_media' => array( 'upload_media' ),
            'wp_ajax_support_delete_media' => array( 'delete_media' )
        ) );
    }
}