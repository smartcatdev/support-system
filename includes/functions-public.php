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
 *      @type callable  $init           Your plugin's init entry-point
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
    $init = \ucare\pluck( $args, 'init' );

    $manager->add_license( $id, $args['store_url'], $args['file'], $options, $edd_args, $init );
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
    return defined( 'UCARE_DEV_MODE' ) && UCARE_DEV_MODE;
}


/**
 * Get the value of a variable based on whether or not the plugin is in development mode. Note, this checks the value of
 * the hard coded constant UCARE_DEV_MODE.
 *
 * @param mixed $prod The value if the plugin is in production
 * @param mixed $dev  The value if the plugin is in development
 *
 * @since 1.6.0
 * @return mixed
 */
function ucare_dev_var( $prod, $dev ) {
    if ( ucare_in_dev_mode() ) {
        return $dev;
    }

    return $prod;
}


/**
 * Validate request Nonces.
 *
 * @param int    $action
 * @param string $nonce
 * @param bool   $die
 *
 * @since 1.6.0
 * @return bool
 */
function ucare_check_nonce( $action = -1, $nonce = '_wpnonce', $die = true ) {
    $valid = \ucare\verify_request_nonce( $action, $nonce );

    if ( !$valid && $die ) {
        wp_die( __( 'Nonce failure', 'ucare' ) );
    }

    return $valid;
}

/**
 * Safely pluck a value from an object or array.
 *
 * @param object|array $obj
 * @param string       $field
 * @param mixed        $default
 *
 * @since 1.6.0
 * @return mixed
 */
function ucare_pluck( $obj, $field, $default = false ) {
    return \ucare\pluck( $obj, $field, $default );
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
 * Get the license manager instance.
 *
 * @since 1.6.0
 * @return \ucare\LicenseManager
 */
function ucare_get_license_manager() {
    return \ucare\ucare()->get( 'license_manager' );
}


/**
 * Get the ticket API instance.
 *
 * @since 1.6.0
 * @return \ucare\API
 */
function ucare_api() {
    return \ucare\ucare()->get( 'api' );
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

    try {
        $options = new \ReflectionClass( $class );

        foreach ( $options->getConstants() as $option ) {
            delete_option( $option );
        }
    } catch ( \Exception $ex ) {
        return;
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


/**
 * Get the current product post type.
 *
 * @since 1.6.0
 * @return bool|string
 */
function ucare_product_post_type() {
    if ( !ucare_is_ecommerce_enabled() ) {
        return false;
    }

    return \ucare\get_product_post_type();
}


/**
 * Get a list of products.
 *
 * @since 1.6.0
 * @return array
 */
function ucare_get_products() {
    if ( !ucare_is_ecommerce_enabled() ) {
        return array();
    }

    return get_posts( array( 'post_type' => ucare_product_post_type(), 'posts_per_page' => -1 ) );
}


/**
 * Get a list of available ticket categories.
 *
 * @param bool $hide_empty
 *
 * @since 1.6.0
 * @return array|int|\WP_Error
 */
function ucare_get_categories( $hide_empty = false ) {
    $args = array(
        'taxonomy'   => 'ticket_category',
        'hide_empty' => $hide_empty
    );
    return get_terms( $args );
}


/**
 * Get a list of ticket categories.
 *
 * @since 1.6.0
 * @return array
 */
function ucare_get_ticket_categories() {
    $terms = get_terms( array( 'taxonomy' => 'ticket_category', 'hide_empty' => false ) );

    if ( !is_array( $terms ) ) {
        return array();
    }

    return $terms;
}

/**
 * Insert or updates a support ticket.
 *
 * @param string|array $args {
 *  Arguments supplied when creating a new support ticket.
 *
 *      @type int        $id
 *      @type int        $author
 *      @type string     $subject
 *      @type string     $body
 *      @type int        $agent
 *      @type string     $status
 *      @type int        $priority
 *      @type int        $product
 *      @type int|string $category
 * }
 *
 * @since 1.6.0
 * @return \WP_Post|\WP_Error
 */
function ucare_insert_ticket( $args ) {
    return ucare_api()->insert_ticket( $args );
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


/**
 * Return the URL path of a production or dev bundle.
 *
 * @param string       $path Relative path to the plugin directory.
 * @param string|array $args
 *
 * @since 1.6.0
 * @return mixed
 */
function ucare_bundle_url( $path, $args = array() ) {
    $defaults = array(
        'plugin_file' => \ucare\plugin_file(),
        'production'  => 'bundle.production.min.js',
        'development' => 'bundle.dev.js'
    );

    if ( is_string( $args ) ) {
        $paths = $defaults;
        $paths['plugin_file'] = $args;
    } else {
        $paths = wp_parse_args( $args, $defaults );
    }

    $dev_src  = trailingslashit( $path ) . $paths['development'];
    $prod_src = trailingslashit( $path ) . $paths['production'];

    if ( ucare_in_dev_mode() && file_exists( \ucare\dir_path( $paths['plugin_file'], $dev_src ) ) ) {
        $url = \ucare\dir_url( $paths['plugin_file'], $dev_src );

    } else if ( file_exists( \ucare\dir_path( $paths['plugin_file'], $prod_src ) ) ) {
        $url = \ucare\dir_url( $paths['plugin_file'], $prod_src );
    }

    if ( !empty( $url ) ) {
        return ucare_cache_bust_url( $url );
    }

    return '';
}


/**
 * Apply cache-busting to a URL if in dev mode.
 *
 * @param string $url
 *
 * @since 1.6.0
 * @return string
 */
function ucare_cache_bust_url( $url ) {
    if ( !ucare_in_dev_mode() ) {
        return $url;
    }
    return add_query_arg( 'dev', wp_rand(), $url );
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
        'after'   => '30 days ago',
        'exclude' => array(),
        'limit'   => -1
    );
    $args = wp_parse_args( $args, $defaults );
    $date = strtotime( $args['after'] );

    $q = array(
        'post_type'      => 'support_ticket',
        'post_status'    => 'publish',
        'author'         => $user->ID,
        'post__not_in'   => $args['exclude'],
        'posts_per_page' => $args['limit'] ?: -1,
        'date_query'     => array(
            array(
                'after' => array(
                    'year'  => date( 'Y', $date ),
                    'month' => date( 'm', $date ),
                    'day'   => date( 'd', $date ),
                )
            )
        )
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

    if ( !$user ) {
        return new \WP_Error( 'user_not_found', __( 'User could not be found', 'ucare' ) );
    }

    $link = \ucare\login_page_url( '?reset_password=true&token=' . \ucare\get_pw_reset_token( $user ) );

    $message =  __( 'Please follow the link to set your password:', 'ucare' ) . ' ' . esc_url_raw( $link );
    $message = apply_filters( 'ucare_pw_reset_message', $message, $user );

    $subject = __( 'Password Reset Requested', 'ucare' );
    $subject = apply_filters( 'ucare_pw_reset_subject', $subject, $user );

    $from_email = get_option( Options::SENDER_EMAIL );
    $from_name  = get_option( Options::SENDER_NAME );

    $headers = (array) "From: {$from_name} <{$from_email}>";

    if ( wp_mail( $user->user_email, $subject, $message, $headers ) ) {
        /**
         *
         * Notify that PW reset has been sent
         *
         *
         * @since 1.7.0
         */
        do_action( 'ucare_pw_reset_sent', $user );

    } else {
        return false;
    }

    return true;
}


/**
 * Get all support agents.
 *
 * @since 1.6.0
 * @return array
 */
function ucare_get_agents() {
    return ucare\get_users_with_cap( 'manage_support_tickets' );
}


/**
 * Get all users who can use the support system.
 *
 * @since 1.6.0
 * @return array
 */
function ucare_get_support_users() {
    return \ucare\get_users_with_cap( 'use_support' );
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


/**
 * Add an admin menu page instance.
 *
 * @param \ucare\MenuPage|string $page
 *
 * @since 1.6.0
 * @return void
 */
function ucare_add_admin_page( $page ) {
    if ( is_string( $page ) && class_exists( $page ) ) {
        $page = new $page();
    }

    $page->add();
}