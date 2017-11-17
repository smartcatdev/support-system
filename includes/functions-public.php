<?php
/***********************************************************************************************************************
 *
 * Functions for managing extension licensing.
 *
 * @since 1.3.0
 * @scope global
 */

/**
 * Register's an extension with the plugin's license management page.
 *

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
 *      @type string    $file           Your plugin's main file
 * }
 *
 * @since 1.3.0
 * @return void
 */
function ucare_register_license( $id, $args ) {

    $manager = \ucare\ucare()->get( 'license_manager' );

    if ( $manager ) {

        $options = array(
            'license'    => $args['license_option'],
            'status'     => $args['status_option'],
            'expiration' => $args['expire_option'],
        );

        $edd_args = array(
            'version'   => $args['version'],
            'author'    => $args['author'],
            'item_name' => $args['item_name']
        );

        $manager->add_license( $id, $args['store_url'], $args['file'], $options, $edd_args );

    }

}

/***********************************************************************************************************************
 *
 * General purpose utility functions.
 *
 * @since 1.3.0
 * @scope global
 */

use ucare\Defaults;
use ucare\Options;
use ucare\util\Logger;

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


/***********************************************************************************************************************
 *
 * Functions for managing assets in the front-end application.
 *
 * @since 1.4.2
 * @scope global
 */


/**
 * Enqueue a script in the front-end application. Can be called at any point before output begins, However enqueuing is
 * not available until after ucare_loaded has fired. It is recommended to enqueue scripts in the ucare_enqueue_scripts
 * hook.
 *
 * @see wp_enqueue_script
 *
 * @param string $handle    The handle of the script to enqueue.
 * @param string $src       The URL of the script.
 * @param array  $deps      Any dependencies the script requires.
 * @param bool   $ver       A version for the script.
 * @param bool   $in_footer Whether the script should be printed in the footer.
 *
 * @since 1.4.2
 * @return void
 */
function ucare_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {

    $scripts = \ucare\scripts();

    if ( $scripts ) {

        if ( $src || $in_footer ) {

            $_handle = explode( '?', $handle );
            $scripts->add( $_handle[0], $src, $deps, $ver );

            if ( $in_footer ) {
                $scripts->add_data( $_handle[0], 'group', 1 );
            }

        }

        $scripts->enqueue( $handle );

    }

}


function ucare_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {

    $styles = \ucare\styles();

    if ( $styles ) {

        if ( $src ) {
            $_handle = explode('?', $handle);
            $styles->add( $_handle[0], $src, $deps, $ver, $media );
        }

        return $styles->enqueue( $handle );

    }

    return false;

}


function ucare_register_script( $handle, $src, $deps = array(), $ver = false, $in_footer = false ) {

    $scripts = \ucare\scripts();

    if ( $scripts ) {

        $registered = $scripts->add( $handle, $src, $deps, $ver );

        if ( $in_footer ) {
            $scripts->add_data( $handle, 'group', 1 );
        }

        return $registered;

    }

    return false;

}


function ucare_localize_script( $handle, $object_name, $i10n ) {

    $scripts = \ucare\scripts();

    if ( $scripts ) {
        $scripts->localize( $handle, $object_name, $i10n );
    }

    return false;

}


function ucare_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {

    $styles = \ucare\styles();

    if ( $styles ) {
        return $styles->add( $handle, $src, $deps, $ver, $media );
    }

    return true;

}
