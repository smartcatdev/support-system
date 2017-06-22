<?php

use ucare\Defaults;
use ucare\Options;
use ucare\util\Logger;

/**
 * Register's an extension with the plugin's license management page.
 *
 * @since 1.3.0
 *
 * @param $id
 * @param array $args {
 *
 *      @type string    $store_url      The url of your EDD store
 *      @type string    $status_option  The option key for saving the license status
 *      @type string    $license_option The option key for saving the license key to
 *      @type string    $expire_option  The option key for saving the license expiration
 *      @type string    $version        The version number of your extension
 *      @type string    $item_name      The item name as it appears in EDD
 *      @type string    $author         The name of the extension author
 *      @type boolean   $beta           Whether or not your extension is in beta
 * }
 *
 * @return boolean True on success, false if the extension has already registered.
 *
 */
function ucare_register_license( $id, $args ) {

    $plugin = \ucare\Plugin::get_plugin( \ucare\PLUGIN_ID );

    return $plugin->add_activation( $id, $args );

}

/**
 * Un-registers an extension from the license management page and cleans up.
 *
 * @since 1.3.0
 *
 * @param $id
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

/**
 * Returns whether or not the plugin is in development mode.
 *
 * @since 1.3.0
 * @return boolean
 */
function ucare_in_dev_mode() {
    return get_option( Options::DEV_MODE, Defaults::DEV_MODE ) == 'on';
}


/**
 * Returns an instance of a logger to save log entries to the logs table.
 *
 * @since 1.3.0
 *
 * @param string $type
 * @return Logger
 */
function ucare_get_logger( $type ) {
    return new Logger( $type );
}