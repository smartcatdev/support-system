<?php
/**
 * Functions for managing WordPress users.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Create a draft ticket for a user
add_action( 'template_redirect', 'ucare\create_user_draft_ticket' );

// Add user roles
add_action( 'init', 'ucare\add_user_roles' );

// Assign caps to user roles
add_action( 'init', 'ucare\add_role_capabilities' );

// Configure caps for ecommerce users
add_action( 'init', 'ucare\set_ecommerce_user_caps' );


/**
 * Configure capabilities for eCommerce users.
 *
 * @action init
 *
 * @since 1.6.0
 * @return void
 */
function set_ecommerce_user_caps() {

    if ( get_option( Options::ECOMMERCE ) ) {
        switch( UCARE_ECOMMERCE_MODE ) {
            case 'edd':
                add_subscriber_caps();
                break;

            case 'woo':
                add_customer_caps();
                break;
        }

    } else {
        revoke_user_level_caps( 'customer' );
        revoke_user_level_caps( 'subscriber' );
    }
}


/**
 * Add customer user roles for the support system.
 *
 * @action init
 *
 * @since 1.5.1
 * @return void
 */
function add_user_roles() {
    $roles = array(
        'support_admin' => __( 'Support Admin', 'ucare' ),
        'support_agent' => __( 'Support Agent', 'ucare' ),
        'support_user'  => __( 'Support User',  'ucare' )
    );

    foreach ( $roles as $role => $name ) {

        if ( is_null( get_role( $role ) ) ) {
            add_role( $role, $name );
        }
    }
}


/**
 * Remove custom roles.
 *
 * @since 1.6.0
 * @return void
 */
function remove_user_roles() {
    $roles = array(
        'support_admin',
        'support_agent',
        'support_user'
    );

    foreach ( $roles as $role => $name ) {

        if ( is_null( get_role( $role ) ) ) {
            remove_role( $role );
        }
    }
}


/**
 * Get a user or role object.
 *
 * @param $user_or_role
 *
 * @since 1.6.0
 * @return mixed
 */
function get_user_or_role( $user_or_role ) {
    $thing = false;

    if ( !is_a( $user_or_role, '\WP_Role' ) || !is_a( $user_or_role, '\WP_User' ) ) {

        if ( is_string( $user_or_role ) ) {
            $thing = get_role( $user_or_role );
        } else if ( is_int( $user_or_role ) ) {
            $thing = get_userdata( $user_or_role );
        }

    }

    return $thing;
}


/**
 * Get a list of the basic capabilities for a user role.
 *
 * @param string $role
 *
 * @since 1.6.0
 * @return false|array
 */
function get_caps_for_role( $role ) {

    $capabilities = array(

        'administrator' => array(

            // System wide access control caps
            'use_support',
            'manage_support',
            'manage_support_tickets',

            // Support_ticket specific caps
            'publish_support_tickets',

            'edit_support_tickets',
            'edit_others_support_tickets',
            'edit_private_support_tickets',
            'edit_published_support_tickets',

            'delete_support_tickets',
            'delete_others_support_tickets',
            'delete_private_support_tickets',
            'delete_published_support_tickets',

            'read_private_support_tickets',

            'edit_support_ticket_comments',
        ),
        'support_admin' => array(

            // System wide access control caps
            'use_support',
            'manage_support',
            'manage_support_tickets',

            // Support_ticket specific caps,
            'publish_support_tickets',

            'edit_others_support_tickets',
            'edit_private_support_tickets',
            'edit_published_support_tickets',

            'delete_support_tickets',
            'delete_others_support_tickets',
            'delete_private_support_tickets',
            'delete_published_support_tickets',

            'read_private_support_tickets',

            'edit_support_ticket_comments',

            // Attachment specific capabilities, Users can publish, delete and read their own media.
            'upload_files',
            'delete_posts',
            'read'
        ),
        'support_agent' => array(

            // System wide access control caps
            'use_support',
            'manage_support_tickets',

            // Support_ticket specific caps. Agents can only create, edit non-published and read others tickets.
            'publish_support_tickets',

            'edit_support_tickets',

            'edit_support_ticket_comments',

            // Attachment specific capabilities, Users can publish, delete and read their own media.
            'upload_files',
            'delete_posts',
            'read'
        ),
        'support_user' => array(

            // System wide access control caps
            'use_support',

            // Support_ticket specific caps. Users can only create, edit non-published and read tickets.
            'publish_support_tickets',
            'edit_support_tickets',

            // Attachment specific capabilities, Users can publish, delete and read their own media.
            'upload_files',
            'delete_posts',
            'read'
        )
    );

    if ( array_key_exists( $role, $capabilities ) ) {
        return $capabilities[ $role ];
    }

    return false;
}


/**
 * Add capabilities to user roles.
 *
 * @action init
 *
 * @since 1.5.1
 * @return void
 */
function add_role_capabilities() {
    $roles = array(
        'administrator',
        'support_admin',
        'support_agent'
    );

    foreach ( $roles as $role ) {
        $caps = get_caps_for_role( $role );
        $role = get_role( $role );

        if ( !is_null( $role ) ) {
            foreach ( $caps as $cap ) {
                $role->add_cap( $cap );
            }
        }
    }

    // Add capabilities to support users
    grant_user_level_caps( 'support_user' );
}


/**
 * Grant support user level capabilities to a user or role.
 *
 * @param $user_or_role int|string|\WP_User|\WP_Role
 *
 * @since 1.5.1
 * @return bool
 */
function grant_user_level_caps( $user_or_role ) {
    $thing = get_user_or_role( $user_or_role );

    if ( $thing ) {

        foreach ( get_caps_for_role( 'support_user' ) as $cap ) {
            $thing->add_cap( $cap );
        }

        return true;
    }

    return false;
}


/**
 * Add support user capabilities to the subscriber role.
 *
 * @param bool $force Skip check to see if EDD is active.
 *
 * @since 1.5.1
 * @return void
 */
function add_subscriber_caps( $force = false ) {

    if ( $force || ucare_ecommerce_mode() === 'edd' ) {
        grant_user_level_caps( 'subscriber' );
    }
}

/**
 * Add support user capabilities to the customer role.
 *
 * @param bool $force Skip check to see if WooCommerce is active.
 *
 * @since 1.5.1
 * @return void
 */
function add_customer_caps( $force = false ) {

    if ( $force || ucare_ecommerce_mode() === 'woo' ) {
        grant_user_level_caps( 'customer' );
    }
}


/**
 * Remove capabilities from user roles.
 *
 * @since 1.5.1
 * @return void
 */
function remove_role_capabilities() {
    $roles = array(
        'administrator',
        'support_admin',
        'support_agent'
    );

    foreach ( $roles as $role ) {
        $caps = get_caps_for_role( $role );
        $role = get_role( $role );

        if ( !is_null( $role ) ) {
            foreach ( $caps as $cap ) {
                $role->remove_cap( $cap );
            }
        }
    }

    revoke_user_level_caps( 'support_user' );
    revoke_user_level_caps( 'subscriber' );
    revoke_user_level_caps( 'customer' );

}


/**
 * Revoke support user level capabilities to a user or role.
 *
 * @param $user_or_role int|string|\WP_User|\WP_Role
 *
 * @return bool
 */
function revoke_user_level_caps( $user_or_role ) {
    $thing = get_user_or_role( $user_or_role );

    if ( $thing ) {

        foreach ( get_caps_for_role( 'support_user') as $cap ) {
            $thing->remove_cap( $cap );
        }

        return true;
    }

    return false;
}


/**
 * @param string $cap
 * @param array $args
 *
 * @since 1.4.2
 * @return array
 */
function get_users_with_cap( $cap = 'use_support', $args = array() ) {
    $result = array();
    $users  = get_users( $args );

    foreach ( $users as $user ) {

        if ( $user->has_cap( $cap ) ) {
            array_push( $result, $user );
        }

    }

    return $result;
}


/**
 * Get a WordPress user, defaults to the current logged in user.
 *
 * @param mixed  $user
 * @param string $by
 *
 * @since 1.5.1
 * @return mixed
 */
function get_user_by_field( $user = null, $by = 'id' ) {
    if ( !$user ) {
        $user = wp_get_current_user();
    } else {
        $user = get_user_by( $by, $user );
    }

    return $user;
}


/**
 * Check to see if a user has a capability.
 *
 * @param string $cap
 * @param int|null  $user_id
 *
 * @since 1.5.1
 * @return boolean
 */
function user_has_cap( $cap, $user_id = null ) {
    $user = get_user_by_field( $user_id );

    if ( $user ) {
        return $user->has_cap( $cap );
    }

    return false;
}


/**
 * Get the value of a single field from a \WP_User object
 *
 * @param string $field The field to get
 * @param mixed  $user
 *
 * @since 1.4.2
 * @return mixed
 */
function get_user_field( $field, $user = null ) {
    $user = get_user( $user );

    if ( $user ) {
        return get_the_author_meta( $field, $user->ID );
    }

    return false;
}


/**
 * Gets a \WP_user object. If no user is passed, will default to the currently logged in user.
 * Returns false if no user can be found.
 *
 * @param mixed $user                The user to get.
 * @param bool  $fallback_to_current Fallback to current user if not found.
 *
 * @since 1.0.0
 * @return false|\WP_User
 */
function get_user( $user = null, $fallback_to_current = true ) {

    if ( empty( $user ) && $fallback_to_current ) {
        $user = wp_get_current_user();
    } else if ( is_numeric( $user ) ) {
        $user = get_userdata( absint( $user ) );
    }

    // Make sure we have a valid support user
    return $user && $user->has_cap( 'use_support' ) ? $user : false;
}


/**
 * Create a post draft for the user when they navigate to the create ticket page.
 *
 * @action template_redirect
 *
 * @since 1.6.0
 * @return void
 */
function create_user_draft_ticket() {

    if ( is_create_ticket_page() && !get_user_draft_ticket() ) {
        $user = get_current_user_id();

        $data = array(
            'post_author' => $user,
            'post_type'   => 'support_ticket',
            'post_status' => 'ucare-auto-draft'
        );

        $id = wp_insert_post( $data );

        if ( is_numeric( $id ) ) {
            update_user_meta( $user, 'draft_ticket', $id );
        }
    }
}


/**
 * Get the draft ticket for the current user.
 *
 * @since 1.6.0
 * @return \WP_Post|false
 */
function get_user_draft_ticket() {

    $draft = get_post( get_user_meta( get_current_user_id(), 'draft_ticket', true ) );

    if ( $draft && $draft->post_status == 'ucare-auto-draft' ) {
        return $draft;
    }

    return false;
}
