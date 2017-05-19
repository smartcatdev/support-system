<?php

class migration_1_2_0 extends smartcat\core\Migration {

    function version() {
        return '1.2.0';
    }

    function migrate( $plugin ) {
        try {

            $tickets = get_posts( array( 'post_type' => 'support_ticket' ) );

            foreach( $tickets as $ticket ) {
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