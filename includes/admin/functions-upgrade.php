<?php
/**
 * Functions for handling plugin updates and migrations.
 *
 * @since 1.6.0
 * @package ucare
 * @subpackage admin
 */
namespace ucare;


// Perform Migrations
add_action( 'init', 'ucare\upgrade_all', 9 );


/**
 * Perform plugin migrations to next version.
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_all() {
    $current_version = get_option( Options::PLUGIN_VERSION );

    // Nothing to do here
    if ( $current_version == PLUGIN_VERSION ) {
        return;
    }

    if ( $current_version < '1.0.0' )
        upgrade_100();

    if ( $current_version < '1.1.1' )
        upgrade_111();

    if ( $current_version < '1.2.0' )
        upgrade_120();

    if ( $current_version < '1.2.1' )
        upgrade_121();

    if ( $current_version < '1.3.0' )
        upgrade_130();

    if ( $current_version < '1.6.0' )
        upgrade_160();

    // Update our plugin version
    update_option( Options::PLUGIN_VERSION, PLUGIN_VERSION );

    /**
     * Notify upgrade has completed
     *
     * @since 1.6.0
     */
    do_action( 'ucare_upgraded', PLUGIN_VERSION );
}


/**
 * Get the contents of an email template.
 *
 * @param string $template
 *
 * @internal
 * @since 1.6.0
 * @return bool|string
 */
function email_template_get_content( $template ) {
    return file_get_contents( resolve_path( 'resources/emails/templates/' . ltrim( $template, '/' ) ) );
}


/**
 * Get the contents of the default email template stylesheet.
 *
 * @internal
 * @since 1.6.0
 * @return bool|string
 */
function email_template_get_default_stylesheet() {
    return file_get_contents( resolve_path( 'resources/emails/stylesheet.css' ) );
}


/**
 * Create a post and save its ID to an option.
 *
 * @param string $option
 * @param array  $data
 *
 * @internal
 * @since 1.6.0
 * @return bool
 */
function create_post_and_set_option( $option, $data ) {
    $id = wp_insert_post( $data );

    if ( $id ) {
        return update_option( $option, $id );
    }

    return false;
}


/**
 * Execute a database upgrade.
 *
 * @global $ucare_db_version
 *
 * @param string $new_version
 *
 * @internal
 * @since 1.6.0
 * @return mixed|string
 */
function _exec_db_upgrade( $new_version ) {
    global $ucare_db_version;

    if ( !function_exists( 'dbDelta' ) ) {
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    }

    /**
     *
     * @since 1.6.0
     */
    $upgraded = apply_filters( 'ucare_upgrade_db', true, $ucare_db_version, $new_version );

    if ( empty( $upgraded ) || is_wp_error( $upgraded ) ) {
        return $upgraded;
    }

    update_option( Options::DATABASE_VERSION, $new_version );
    return $ucare_db_version = $new_version;
}


/**
 * Execute changes made in version 1.0.0
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_100() {
    /**
     *
     * Create the main application page
     */
    $data = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_title'  => __( 'Support', 'ucare' )
    );

    create_post_and_set_option( Options::TEMPLATE_PAGE_ID, $data );

    /**
     *
     * Create email templates
     */
    $styles = email_template_get_default_stylesheet();

    $emails = array(
        Options::TICKET_CREATED_EMAIL => array(
            'post_title'   => __( 'You have created a new request for support', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'ticket-created.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        ),
        Options::WELCOME_EMAIL_TEMPLATE => array(
            'post_title'   => __( 'Welcome to Support', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'welcome.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        ),
        Options::TICKET_CLOSED_EMAIL_TEMPLATE => array(
            'post_title'   => __( 'Your request for support has been closed', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'ticket-closed.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        ),
        Options::AGENT_REPLY_EMAIL => array(
            'post_title'   => __( 'Reply to your request for support', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'ticket-reply.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        ),
        Options::PASSWORD_RESET_EMAIL => array(
            'post_title'   => __( 'Your password has been reset', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'password-reset.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        )
    );

    foreach ( $emails as $option => $data ) {
        create_post_and_set_option( $option, $data );
    }

    error_log( 'uCare upgraded to 1.0.0' );
}


/**
 * Execute changes made in version 1.1.0
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_111() {
    $data = array(
        'post_type'    => 'email_template',
        'post_status'  => 'publish',
        'post_title'   => __( 'Your password has been changed', 'ucare' ),
        'post_content' => email_template_get_content( 'password-reset.html' ),
        'meta_input'   => array(
            'styles' => email_template_get_default_stylesheet()
        )
    );

    create_post_and_set_option( Options::PASSWORD_RESET_EMAIL, $data );
    error_log( 'uCare upgraded to 1.1.1' );
}


/**
 * Execute changes made in version 1.2.0
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_120() {
    /**
     *
     * Separate "closed" meta array into separate keys
     */
    $args = array(
        'post_type'      => 'support_ticket',
        'posts_per_page' => -1
    );

    $q = new \WP_Query( $args );

    foreach ( $q->posts as $ticket ) {
        $old_meta = get_post_meta( $ticket->ID, 'closed', true );

        if ( !empty( $old_meta ) ) {
            update_post_meta( $ticket->ID, 'closed_by',   $old_meta['user_id'] );
            update_post_meta( $ticket->ID, 'closed_date', $old_meta['date'] );
            delete_post_meta( $ticket->ID, 'closed' );
        }
    }

    /**
     *
     * Schedule new cron jobs
     */
    wp_clear_scheduled_hook( 'ucare\cron\stale_tickets' );
    wp_clear_scheduled_hook( 'ucare\cron\close_tickets' );

    if ( !wp_next_scheduled( 'ucare_cron_stale_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_stale_tickets' );
    }

    if ( !wp_next_scheduled( 'ucare_cron_close_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_close_tickets' );
    }

    error_log( 'uCare upgraded to 1.2.0' );
}


/**
 * Execute changes made in version 1.2.1
 *
 * @global $wpdb
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_121() {
    /**
     *
     * Execute a db table upgrade
     */
    _exec_db_upgrade( '1.2.1' );

    /**
     *
     * Create closed warning email template
     */
    $data = array(
        'post_type'    => 'email_template',
        'post_status'  => 'publish',
        'post_title'   => __( 'You have a ticket awaiting action', 'ucare' ),
        'post_content' => email_template_get_content( 'ticket-close-warning.html' ),
        'meta_input'   => array(
            'styles' => email_template_get_default_stylesheet()
        )
    );

    create_post_and_set_option( Options::INACTIVE_EMAIL, $data );
    error_log( 'uCare upgraded to 1.2.1' );
}


/**
 * Execute changes made in version 1.3.0
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_130() {
    /**
     *
     * Insert email templates
     */
    $styles = email_template_get_default_stylesheet();

    $emails = array(
        Options::TICKET_ASSIGNED => array(
            'post_title'   => __( 'You have been assigned a ticket', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'agent-ticket-assigned.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        ),
        Options::CUSTOMER_REPLY_EMAIL => array(
            'post_title'   => __( 'You have a reply to a ticket that you are assigned to', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'agent-ticket-reply.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        ),
        Options::NEW_TICKET_ADMIN_EMAIL => array(
            'post_title'   => __( 'A new ticket has been created', 'ucare' ),
            'post_type'    => 'email_template',
            'post_status'  => 'publish',
            'post_content' => email_template_get_content( 'admin-ticket-created.html' ),
            'meta_input'   => array(
                'styles' => $styles
            )
        )
    );

    foreach ( $emails as $option => $data ) {
        create_post_and_set_option( $option, $data );
    }

    error_log( 'uCare upgraded to 1.3.0' );
}


/**
 * Execute changes made in version 1.6.0
 *
 * @since 1.6.0
 * @return void
 */
function upgrade_160() {
    /**
     *
     * Execute DB upgrade
     */
    _exec_db_upgrade( '1.6.0' );

    /**
     *
     * Add new application sub pages
     */
    $parent = get_post( get_option( Options::TEMPLATE_PAGE_ID ) );

    if ( $parent ) {
        $pages = array(
            Options::CREATE_TICKET_PAGE_ID => array(
                'post_title'  => __( 'Create Ticket', 'ucare' ),
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $parent->ID
            ),
            Options::EDIT_PROFILE_PAGE_ID => array(
                'post_title'  => __( 'Edit Profile', 'ucare' ),
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $parent->ID
            ),
            Options::LOGIN_PAGE_ID => array(
                'post_title'  => __( 'Login', 'ucare' ),
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $parent->ID
            )
        );

        foreach ( $pages as $option => $data ) {
            create_post_and_set_option( $option, $data );
        }
    }

    /**
     *
     * Remove old unused capabilities
     */
    $remove = array(
        'administrator' => array(
            'read_support_ticket',
            'edit_support_ticket',
            'create_support_tickets',
        ),
        'support_admin' => array(
            'create_support_tickets',
            'edit_support_ticket_comments',
        ),
        'support_agent' => array(
            'create_support_tickets',
            'edit_support_ticket_comments'
        ),
        'support_user' => array(
            'create_support_tickets',
        ),
        'customer' => array(
            'create_support_tickets',
        ),
        'subscriber' => array(
            'create_support_tickets',
        ),
    );

    foreach ( $remove as $role => $caps ) {
        $role = get_role( $role );

        if ( !is_null( $role ) ) {
            foreach ( $caps as $cap ) {
                $role->remove_cap( $cap );
            }
        }
    }

    /**
     *
     * Clear old license check cron
     */
    if ( wp_next_scheduled( 'ucare_check_extension_licenses' ) ) {
        wp_clear_scheduled_hook( 'ucare_check_extension_licenses' );
    }

    error_log( 'uCare upgraded to 1.6.0' );
}
