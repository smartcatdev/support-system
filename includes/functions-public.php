<?php

use ucare\Defaults;
use ucare\Options;
use ucare\Logger;

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

    if ( !$manager ) {
        return;
    }

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


/**
 * Generate a dropdown from an associative array of options.
 *
 * @param array  $options
 * @param string $selected
 * @param array  $attributes
 *
 * @since 1.6.0
 * @return void
 */
function ucare_dropdown( $options, $selected = '', $attributes = array() ) {
    \ucare\dropdown( $options, $selected, $attributes );
}


/**
 * Generate a checkbox field.
 *
 * @param string $name
 * @param string $label
 * @param string $value
 * @param bool   $checked
 * @param array  $attributes
 *
 * @since 1.6.0
 * @return void
 */
function ucare_checkbox( $name, $label = '', $checked = false, $value = '', $attributes = array() ) {
    \ucare\checkbox( $name, $label, $checked, $value, $attributes );
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
 * Drop an options class
 *
 * @param string $class
 *
 * @since 1.6.0
 * @return void
 */
function ucare_drop_options( $class ) {
    if ( !class_exists( $class ) && !interface_exists( $class ) ) {
        return;
    }

    $options = new \ReflectionClass( $class );

    foreach ( $options->getConstants() as $option ) {
        delete_option( $option );
    }
}


/**
 * Get the current plugin version.
 *
 * @since 1.6.0
 * @return string
 */
function ucare_version() {
    return get_option( Options::PLUGIN_VERSION );
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

    if ( !$scripts ) {
        return;
    }

    if ( $src || $in_footer ) {
        $_handle = explode( '?', $handle );
        $scripts->add( $_handle[0], $src, $deps, $ver );

        if ( $in_footer ) {
            $scripts->add_data( $_handle[0], 'group', 1 );
        }
    }

    $scripts->enqueue( $handle );
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

    if ( !$styles ) {
        return;
    }

    if ( $src ) {
        $_handle = explode('?', $handle);
        $styles->add( $_handle[0], $src, $deps, $ver, $media );
    }

    $styles->enqueue( $handle );
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

    if ( !$scripts ) {
        return false;
    }

    $registered = $scripts->add( $handle, $src, $deps, $ver );

    if ( $in_footer ) {
        $scripts->add_data( $handle, 'group', 1 );
    }

    return $registered;
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
 * @global $wpdb
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
 *
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
        \ucare\ucare()->set( 'sidebars', array_merge( $top, array( $id => $section ), $sidebars ) );
    }
}


/***********************************************************************************************************************
 *
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


/***********************************************************************************************************************
 *
 * General purpose template functions
 *
 * @since 1.6.0
 * @scope global
 */


/**
 * Get the support system header.
 *
 * @param array $args
 *
 * @since 1.6.0
 * @return void
 */
function ucare_get_header( $args = array() ) {
    \ucare\get_template( 'header', $args );
}


/**
 * Get the support system footer.
 *
 * @param array $args
 *
 * @since 1.6.0
 * @return void
 */
function ucare_get_footer( $args = array() ) {
    \ucare\get_template( 'footer', $args );
}


/**
 * Fires the ucare_head action.
 *
 * @since 1.0.0
 * @return void
 */
function ucare_head() {
    /**
     * Prints scripts or data in the head tag on the front end.
     *
     * @since 1.6.0
     */
    do_action( 'ucare_head' );
}


/**
 * Fire the ucare_footer action.
 *
 * @since 1.6.0
 * @return void
 */
function ucare_footer() {
    /**
     * Prints scripts or data before the closing body tag on the front end.
     *
     * @since 1.6.0
     */
    do_action( 'ucare_footer' );
}



/***********************************************************************************************************************
 *
 * Functions for managing users
 *
 * @since 1.6.0
 * @scope global
 */

/**
 * Register a user with the support system.
 *
 * @param array $user_data
 * @param bool  $authenticate
 * @param bool  $remember
 *
 * @since 1.6.0
 * @return int|\WP_Error
 */
function ucare_register_user( $user_data, $authenticate = false, $remember = false ) {
    if ( empty( $user_data['email'] ) ) {
        return new \WP_Error( 'user_email_missing', __( 'An email address must be provided', 'ucare' ), array( 'status' => 400 ) );
    }

    $defaults = array(
        'password'   => wp_generate_password(),
        'role'       => 'support_user',
        'first_name' => '',
        'last_name'  => '',
    );

    $user_data = wp_parse_args( $user_data, $defaults );

    $map = array(
        'user_login'    => $user_data['email'],
        'user_email'    => $user_data['email'],
        'first_name'    => $user_data['first_name'],
        'last_name'     => $user_data['last_name'],
        'role'          => $user_data['role'],
        'user_pass'     => $user_data['password']
    );

    $id = wp_insert_user( $map );

    if ( is_wp_error( $id ) ) {
        return $id;
    }

    // Auto authenticate if another user is not logged in
    if ( $authenticate && !is_user_logged_in() ) {
        wp_set_auth_cookie( $id, $remember );
    }

    do_action( 'support_user_registered', $user_data );

    return $id;
}


/**
 * Reset a user's password.
 *
 * @param string $username
 *
 * @since 1.6.0
 * @return bool|\WP_Error
 */
function ucare_reset_user_password( $username ) {
    $user = get_user_by( 'email', $username );

    if ( !$user ) {
        $user = get_user_by( 'login', $username );
    }

    if ( $user ) {
        $password = wp_generate_password();

        // Update the password
        wp_set_password( $password, $user->ID );

        /**
         * @since 1.0.0
         * @deprecated
         */
        apply_filters( 'support_password_reset_notification', true, $user->user_email, $password, $user );

        /**
         * @since 1.6.0
         */
        do_action( 'support_password_reset', $user, $password );

        return true;
    }

    return new \WP_Error( 'user_not_found', __( 'User could not be found', 'ucare' ), array( 'status' => 404 ) );
}


/***********************************************************************************************************************
 *
 * General purpose functions for managing templates.
 *
 * @since 1.6.0
 * @scope global
 */


/**
 * Helper to quickly output a dropdown of valid products.
 *
 * @param string $selected
 * @param array  $attributes
 * @param array  $prepend
 *
 * @since 1.6.0
 * @return void
 */
function ucare_products_dropdown( $selected = '', $attributes = array(), $prepend = array() ) {
    $products = array();

    foreach ( get_posts( array( 'post_type' => \ucare\get_product_post_type() ) ) as $product ) {
        $products[ $product->ID ] = $product->post_title;
    }

    if ( !empty( $prepend ) ) {
        $products = $prepend + $products;
    }

    \ucare\dropdown( $products, $selected, $attributes );
}


/**
 * Helper to quickly output a dropdown of valid ticket statuses.
 *
 * @param string $selected
 * @param array  $attributes
 * @param array  $prepend
 *
 * @since 1.6.0
 * @return void
 */
function ucare_statuses_dropdown( $selected = '', $attributes = array(), $prepend = array() ) {
    $statuses = \ucare\get_ticket_statuses();

    if ( !empty( $prepend ) ) {
        $statuses = $prepend + $statuses;
    }

    \ucare\dropdown( $statuses, $selected, $attributes );
}


/**
 * Helper to quickly output a dropdown of valid ticket priorities.
 *
 * @param string $selected
 * @param array  $attributes
 * @param array  $prepend
 *
 * @since 1.6.0
 * @return void
 */
function ucare_priority_dropdown( $selected = '', $attributes = array(), $prepend = array() ) {
    $priorities = \ucare\ticket_priorities();

    if ( !empty( $prepend ) ) {
        $priorities = $prepend + $priorities;
    }

    \ucare\dropdown( $priorities, $selected, $attributes );
}


/**
 * Helper to quickly output a dropdown of valid ticket categories.
 *
 * @param string $selected
 * @param array  $attributes
 * @param array  $prepend
 *
 * @since 1.6.0
 * @return void
 */
function ucare_category_dropdown( $selected = '', $attributes = array(), $prepend = array() ) {
    $categories = array();

    foreach ( get_terms( array( 'taxonomy' => 'ticket_category', 'hide_empty' => false ) ) as $category ) {
        $categories[ $category->term_id ] = $category->name;
    }

    if ( !empty( $prepend ) ) {
        $categories = $prepend + $categories;
    }

    \ucare\dropdown( $categories, $selected, $attributes );
}


/**
 * Helper to quickly output a dropdown of valid support agents.
 *
 * @param string $selected
 * @param array  $attributes
 * @param array  $prepend
 *
 * @since 1.6.0
 * @return void
 */
function ucare_agents_dropdown( $selected = '', $attributes = array(), $prepend = array() ) {
    $agents = array();

    foreach ( get_users() as $user ) {

        if ( ucare_is_support_agent( $user->ID ) ) {
            $agents[ $user->ID ] = $user->display_name;
        }
    }

    if ( !empty( $prepend ) ) {
        $agents = $prepend + $agents;
    }

    \ucare\dropdown( $agents, $selected, $attributes );
}


/***********************************************************************************************************************
 *
 * Helper functions for the WordPress admin
 *
 * @since 1.6.0
 * @scope global
 */

/**
 * Check to see if we are on a admin menu screen.
 *
 * @param string $tag
 *
 * @since 1.6.0
 * @return bool
 */
function ucare_admin_is_screen( $tag ) {
    if ( !is_admin() ) {
        return false;
    }

    $screen = get_current_screen();

    switch ( $tag ) {
        case 'settings':
            return $screen->id === 'ucare-support_page_uc-settings';

        case 'reports':
            return $screen->id === 'toplevel_page_ucare_support';
    }

    return false;
}
