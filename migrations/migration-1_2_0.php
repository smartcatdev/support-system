<?php

use ucare\descriptor\Option;

class migration_1_2_0 implements smartcat\core\Migration {

    private $plugin;

    function version() {
        return '1.2.0';
    }

    /**
     * 1. Separate closed meta keys from serialized array to individual keys
     * 2. Add email template for ticket closure warnings and update default option
     * 3. Setup cron job for closing tickets
     *
     * @return mixed
     */
    function migrate( $plugin ) {
        error_log( '1.2.0' );

        $this->plugin = $plugin;
        
        $this->separate_meta_keys();
        $this->setup_cron();

        return array( 'success' => true, 'message' => 'uCare has been successfully upgraded to version 1.2.0' );
    }

    function separate_meta_keys() {
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
    }

    function setup_cron() {
        wp_clear_scheduled_hook( 'ucare\cron\stale_tickets' );
        wp_clear_scheduled_hook( 'ucare\cron\close_tickets' );

        ucare\proc\schedule_cron_jobs();
    }
}

return new migration_1_2_0();
