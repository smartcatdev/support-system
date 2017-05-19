<?php

use SmartcatSupport\descriptor\Option;

class migration_1_1_1 implements \smartcat\core\Migration {

    function version () {
        return '1.1.1';
    }

    /**
     * 1. Insert the default password reset template
     *
     * @return mixed
     */
    function migrate ( $plugin ) {
        error_log( '1.1.1' );

        try {
            $dir = \SmartcatSupport\plugin_dir();

            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => __( 'Your password has been changed', \SmartcatSupport\PLUGIN_ID ),
                    'post_content'  => file_get_contents( $dir . '/emails/password-reset.html' )
                )
            );

            if( !empty( $id ) ) {
                update_post_meta( $id, 'styles', file_get_contents( $dir . '/emails/default-style.css' ) );
                update_option( Option::PASSWORD_RESET_EMAIL, $id );
            }

        } catch( Exception $ex ) { }

        return array( 'success' => true, 'message' => 'uCare has been successfully upgraded to version 1.1.1' );
    }

}

return new migration_1_1_1();
