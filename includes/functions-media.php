<?php
/**
 * Functions for handling ticket media.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Toggle the media directory
add_action( 'init', 'ucare\maybe_override_media_dir' );

// Filter the media directory
add_filter( 'upload_dir', 'ucare\filter_media_dir' );

// Validate the mime type of an attachment
add_filter( 'wp_handle_upload_prefilter', 'ucare\check_attachment_mime_type' );

// Set attachment error handler
add_filter( 'wp_handle_upload_prefilter', 'ucare\generate_attachment_uuid' );

// Check for custom media cap
add_filter( 'wp_handle_upload_prefilter', 'ucare\check_media_capabilities' );


/**
 * Check if the user can access support_uploads folder.
 *
 * @action wp_handle_upload_prefilter
 *
 * @param $file
 *
 * @todo Case will needed to be handle for deleting media when we switch that to REST
 * @since 1.6.0
 * @return mixed
 */
function check_media_capabilities( $file ) {
    if ( !current_user_can( 'upload_support_media' ) ) {
        $file['error'] = __( 'you don\'t have permission to upload support media', 'ucare' );
    }

    return $file;
}


/**
 * Check the request to see if we should override the default media directory.
 *
 * @action init
 *
 * @since 1.5.1
 * @return void
 */
function maybe_override_media_dir() {
    if ( get_var( 'support_ticket_media' ) ) {
        define( 'UCARE_MEDIA_BASEDIR', 'support_uploads' );
    }
}


/**
 * Set the media directory for a support ticket attachment.
 *
 * @param $uploads
 *
 * @filter upload_dir
 *
 * @since 1.5.1
 * @return array
 */
function filter_media_dir( $uploads ) {
    if ( defined( 'UCARE_MEDIA_BASEDIR' ) ) {

        $dir = $uploads['basedir'];
        $url = $uploads['baseurl'];

        $user = wp_get_current_user();

        return array(
            'path'    => strcat( $dir, '/', UCARE_MEDIA_BASEDIR, '/', $user->ID ),
            'url'     => strcat( $url, '/', UCARE_MEDIA_BASEDIR, '/', $user->ID ),
            'subdir'  => '',
            'basedir' => $dir,
            'baseurl' => $url,
            'error'   => false,
        );

    }

    return $uploads;
}

/**
 * Validate the mime type of an attachment.
 *
 * @param $file
 *
 * @filter wp_handle_upload_prefilter
 *
 * @since 1.5.1
 * @return array
 */
function check_attachment_mime_type( $file ) {
    if ( defined( 'UCARE_MEDIA_BASEDIR' ) && !in_array( $file['type'], allowed_mime_types() ) ) {
        $file['error'] = __( 'Invalid file format', 'ucare' );
    }

    return $file;
}


/**
 * Generate a new UUID for the an attachment filename.
 *
 * @param $file
 *
 * @filter wp_handle_upload_prefilter
 *
 * @since 1.5.1
 * @return array
 */
function generate_attachment_uuid( $file ) {
    if ( defined( 'UCARE_MEDIA_BASEDIR' ) ) {
        $file['name'] = wp_generate_uuid4() . '.' . get_file_extension( $file['name'] );
    }

    return $file;
}


/**
 * Get the extension from a file name.
 *
 * @param string $name
 *
 * @since 1.5.1
 * @return string
 */
function get_file_extension( $name ) {
    $ext = explode( '.', $name );
    return end( $ext );
}
