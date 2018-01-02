<?php

use ucare\Defaults;
use ucare\Options;
use ucare\util\Logger;

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


/**
 * Check to see if eCommerce support is enabled.
 *
 * @since 1.4.2
 * @return bool
 */
function ucare_is_ecommerce_enabled() {
    return defined( 'UCARE_ECOMMERCE_MODE' );
}


/**
 * Get the current eCommerce mode.
 *
 * @since 1.5.1
 * @return bool|string
 */
function ucare_ecommerce_mode() {

    if ( defined( 'UCARE_ECOMMERCE_MODE' ) ) {
        return UCARE_ECOMMERCE_MODE;
    }

    return false;

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


/**
 * Enqueue a style in the front-end application. Can be called at any point before output begins, However enqueuing is
 * not available until after ucare_loaded has fired. It is recommended to enqueue scripts in the ucare_enqueue_scripts
 * hook.
 *
 * @see wp_enqueue_script
 *
 * @param string $handle    The handle of the script to enqueue.
 * @param string $src       The URL of the script.
 * @param array  $deps      Any dependencies the script requires.
 * @param bool   $ver       A version for the script.
 * @param string $media     Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts media
 *                          types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)'
 *                          and '(max-width: 640px)'.
 *
 * @since 1.4.2
 * @return void
 */
function ucare_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {

    $styles = \ucare\styles();

    if ( $styles ) {

        if ( $src ) {
            $_handle = explode('?', $handle);
            $styles->add( $_handle[0], $src, $deps, $ver, $media );
        }

        $styles->enqueue( $handle );
    }

}


/**
 * Register a script in the front-end application. Can be called at any point before output begins, However enqueuing is
 * not available until after ucare_loaded has fired. It is recommended to enqueue scripts in the ucare_enqueue_scripts
 * hook.
 *
 * @see \wp_register_script
 *
 * @param string $handle    The handle of the script to enqueue.
 * @param string $src       The URL of the script.
 * @param array  $deps      Any dependencies the script requires.
 * @param bool   $ver       A version for the script.
 * @param bool   $in_footer Whether the script should be printed in the footer.
 *
 * @since 1.4.2
 * @return bool
 */
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


/**
 * Localizes a script in the front-end application with a JSON object array.
 *
 * @see \wp_localize_script
 *
 * @param string       $handle      The name of the script to localize.
 * @param string       $object_name The name of the localized object.
 * @param array        $i10n        The localization values.
 *
 * @since 1.4.2
 * @return bool
 */
function ucare_localize_script( $handle, $object_name, $i10n ) {

    $scripts = \ucare\scripts();

    if ( $scripts ) {
        $scripts->localize( $handle, $object_name, $i10n );
    }

    return false;

}

/**
 * Register a style in the front-end application. Can be called at any point before output begins, However enqueuing is
 * not available until after ucare_loaded has fired. It is recommended to enqueue scripts in the ucare_enqueue_scripts
 * hook.
 *
 * @see \wp_register_style
 *
 * @param string $handle    The handle of the script to enqueue.
 * @param string $src       The URL of the script.
 * @param array  $deps      Any dependencies the script requires.
 * @param bool   $ver       A version for the script.
 * @param string $media     Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts media
 *                          types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)'
 *                          and '(max-width: 640px)'.
 *
 * @since 1.4.2
 * @return bool
 */
function ucare_register_style( $handle, $src, $deps = array(), $ver = false, $media = 'all' ) {

    $styles = \ucare\styles();

    if ( $styles ) {
        return $styles->add( $handle, $src, $deps, $ver, $media );
    }

    return true;

}



/***********************************************************************************************************************
 *
 * Functions for plugin statistics
 *
 * @since 1.4.2
 * @scope global
 */


/**
 * Get a list of recent tickets for a specific user.
 *
 * @param $user
 * @param array $args
 *
 * @since 1.0.0
 * @return \WP_Query
 */
function ucare_get_user_recent_tickets( $user, $args = array() ) {

    $user = \ucare\get_user( $user );

    $defaults = array(
        'after'   => 'now',
        'before'  => '30 days ago',
        'exclude' => array(),
        'limit'   => -1
    );

    $args = wp_parse_args( $args, $defaults );

    $q = array(
        'post_type'      => 'support_ticket',
        'post_status'    => 'publish',
        'author'         => $user->ID,
        'after'          => $args['after'],
        'before'         => $args['before'],
        'post__not_in'   => $args['exclude'],
        'posts_per_page' => $args['limit'] ?: -1
    );

    return new \WP_Query( $q );

}


/**
 * Count the number of tickets that a user has created.
 *
 * @param int $user_id The ID of the user.
 *
 * @since 1.0.0
 * @return int
 */
function ucare_count_user_tickets( $user_id ) {

    global $wpdb;

    $sql = "SELECT COUNT( * )
            FROM $wpdb->posts
            WHERE post_author = %d 
              AND post_type = 'support_ticket'
              AND post_status = 'publish'";

    return $wpdb->get_var( $wpdb->prepare( $sql, $user_id ) );

}


/***********************************************************************************************************************
 * General template functions
 *
 * @since 1.4.2
 * @scope global
 */

/**
 * Register a section to display in the ticket sidebar.
 *
 * @param string $id       The section ID.
 * @param int    $position Where the sidebar should display.
 * @param array  $section  The sidebar section.
 *
 * @since 1.0.0
 * @return void
 */
function ucare_register_sidebar( $id, $position, array $section ) {

    $sidebars = \ucare\get_sidebars();

    if ( is_array( $sidebars ) ) {
        $top = array_slice( $sidebars, 0, $position );
        $new = array( $id => $section );

        \ucare\ucare()->set( 'sidebars', array_merge( $top, $new, $sidebars ) );
    }

}


/***********************************************************************************************************************
 * General user functions
 *
 * @since 1.5.1
 * @scope global
 */


/**
 * Check to see if a user can use support. Defaults to the current user or takes the user ID.
 *
 * @param null|int $user_id
 *
 * @since 1.5.1
 * @return boolean
 */
function ucare_is_support_user( $user_id = null ) {
    return \ucare\user_has_cap( 'use_support', $user_id );
}


/**
 * Check to see if a user can manage support tickets. Defaults to the current user or takes the user ID.
 *
 * @param null|int $user_id
 *
 * @since 1.5.1
 * @return boolean
 */
function ucare_is_support_agent( $user_id = null ) {
    return \ucare\user_has_cap( 'manage_support_tickets', $user_id );
}


/**
 * Check to see if a user can administer support. Defaults to the current user or takes the user ID.
 *
 * @param null|int $user_id
 *
 * @since 1.5.1
 * @return boolean
 */
function ucare_is_support_admin( $user_id = null ) {
    return \ucare\user_has_cap( 'manage_support', $user_id );
}
