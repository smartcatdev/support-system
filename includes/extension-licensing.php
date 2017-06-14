<?php

namespace ucare;

function init_extension_licensing() {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    foreach( $plugin->get_activations() as $id => $edd_options ) {

        $sanitize = function ( $new ) use ( $edd_options ) {
            $old = get_option( $edd_options['license_option'] );

            if( $old && $old != $new ) {
                delete_option( $edd_options['license_option'] );
            }

            return $new;
        };

        register_setting( 'ucare_extension_licenses', $edd_options['license_option'], $sanitize );
        register_setting( 'ucare_extension_licenses', $edd_options['status_option'] );

        $edd_updater = new \EDD_SL_Plugin_Updater(
            $edd_options['store_url'],
            $edd_options['support_file'],
            $edd_options['plugin_info']
        );

    }

}

add_action( 'admin_init', 'ucare\init_extension_licensing' );

