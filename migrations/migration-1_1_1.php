<?php

class migration_1_1_1 implements \SmartcatSupport\util\Migration {

    function version () {
        return '1.1.1';
    }

    /**
     * 1. Update any attachments with post_status = 'private'
     *
     * @return bool|WP_Error
     */
    function migrate () {
        try {
            $attachments = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'private' ) );

            $attachments = array_filter( $attachments, function ( $attachment ) {
                return get_post( $attachment->post_parent )->post_type == 'support_ticket';
            } );

            foreach ( $attachments as $attachment ) {
                wp_update_post( array(
                    'ID' => $attachment->ID,
                    'post_status' => 'inherit'
                ) );
            }

        } catch ( Exception $ex ) {
            return new WP_Error( __( 'Migration failed', \SmartcatSupport\PLUGIN_ID ) );
        }

        return true;
    }

}

return new migration_1_1_0();
