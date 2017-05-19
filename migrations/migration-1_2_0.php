<?php


class migration_1_2_0 implements smartcat\core\Migration {

    function version() {
        return '1.2.0';
    }

    /**
     * 1. Separate closed meta keys from serialized array to individual keys
     *
     * @return mixed
     */
    function migrate( $plugin ) {
        error_log( '1.2.0' );

        try {

            $q = new \WP_Query( array(
                'post_type'      => 'support_ticket',
                'posts_per_page' => -1
            ) );

            foreach( $q->posts as $ticket ) {
                $old_meta = get_post_meta( $ticket->ID, 'closed', true );

                if( !empty( $old_meta ) ) {
                    update_post_meta( $ticket->ID, 'closed_by', $old_meta['user_id'] );
                    update_post_meta( $ticket->ID, 'closed_date', $old_meta['date'] );

                    delete_post_meta( $ticket->ID, 'closed' );
                }
            }

        } catch ( Exception $ex ) {}

        return array( 'success' => true, 'message' => 'uCare has been successfully upgraded to version 1.2.0' );
    }
}

return new migration_1_2_0();
