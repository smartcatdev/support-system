<?php

/**
 * @param $id
 * @param $args
 * @return mixed
 * @TODO document args
 *
 */
function ucare_register_license( $id, $args ) {

    $plugin = \ucare\Plugin::get_plugin( \ucare\PLUGIN_ID );

    return $plugin->add_activation( $id, $args );

}

/**
 * @param $id
 * @TODO document
 */
function ucare_unregister_license( $id ) {

    $plugin = \ucare\Plugin::get_plugin( \ucare\PLUGIN_ID );
    $activation = $plugin->get_activation( $id );

    if( $activation ) {

        delete_option( $activation['status_option'] );
        delete_option( $activation['license_option'] );
        delete_option( $activation['expire_option'] );

        unregister_setting( 'ucare_extension_licenses', $activation['license_option'] );
    }

}
