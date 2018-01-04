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


/**
 * Add customer user roles for the support system.
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
 * Add capabilities to user roles.
 *
 * @since 1.5.1
 * @return void
 */
function add_role_capabilities() {

    // Add capabilities to administrator
    $role = get_role( 'administrator' );

    if ( $role ) {
        /**
         *
         * System wide access control caps
         */
        $role->add_cap( 'use_support' );
        $role->add_cap( 'manage_support' );
        $role->add_cap( 'manage_support_tickets' );

        /**
         *
         * support_ticket specific caps
         */
        $role->add_cap( 'publish_support_tickets' );

        $role->add_cap( 'edit_support_tickets' );
        $role->add_cap( 'edit_others_support_tickets' );
        $role->add_cap( 'edit_private_support_tickets' );
        $role->add_cap( 'edit_published_support_tickets' );

        $role->add_cap( 'delete_support_tickets' );
        $role->add_cap( 'delete_others_support_tickets' );
        $role->add_cap( 'delete_private_support_tickets' );
        $role->add_cap( 'delete_published_support_tickets' );

        $role->add_cap( 'read_private_support_tickets' );

        /**
         *
         * Administrator already has full control over media and comments
         */
    }


    // Add capabilities to support admins
    $role = get_role( 'support_admin' );

    if ( $role ) {
        /**
         *
         * System wide access control caps
         */
        $role->add_cap( 'use_support' );
        $role->add_cap( 'manage_support' );
        $role->add_cap( 'manage_support_tickets' );

        /**
         *
         * support_ticket specific caps
         */
        $role->add_cap( 'publish_support_tickets' );

        $role->add_cap( 'edit_support_tickets' );
        $role->add_cap( 'edit_others_support_tickets' );
        $role->add_cap( 'edit_private_support_tickets' );
        $role->add_cap( 'edit_published_support_tickets' );

        $role->add_cap( 'delete_support_tickets' );
        $role->add_cap( 'delete_others_support_tickets' );
        $role->add_cap( 'delete_private_support_tickets' );
        $role->add_cap( 'delete_published_support_tickets' );

        $role->add_cap( 'read_private_support_tickets' );

        /**
         *
         * attachment specific capabilities, Users can publish, delete and read their own media.
         */
        $role->add_cap( 'upload_files' );
        $role->add_cap( 'delete_posts' );
        $role->add_cap( 'read' );
    }


    // Add capabilities to support agents
    $role = get_role( 'support_agent' );

    if ( $role ) {
        /**
         *
         * System wide access control caps
         */
        $role->add_cap( 'use_support' );
        $role->add_cap( 'manage_support_tickets' );

        /**
         *
         * support_ticket specific caps. Agents can only create, edit non-published and read others tickets.
         */
        $role->add_cap( 'publish_support_tickets' );
        $role->add_cap( 'edit_support_tickets' );

        /**
         *
         * attachment specific capabilities, Users can publish, delete and read their own media.
         */
        $role->add_cap( 'upload_files' );
        $role->add_cap( 'delete_posts' );
        $role->add_cap( 'read' );
    }


    // Add capabilities to support users
    grant_user_level_caps( 'support_user' );

    // If EDD is active
    add_subscriber_caps();

    // If Woo is active
    add_customer_caps();

}


/**
 * Grant support user level capabilities to a user or role.
 *
 * @param $user_or_role int|string|\WP_User|\WP_Role
 *
 * @return bool
 */
function grant_user_level_caps( $user_or_role ) {

    $thing = get_user_or_role( $user_or_role );

    if ( $thing ) {

        /**
         *
         * System wide access control caps
         */
        $thing->add_cap( 'use_support' );

        /**
         *
         * support_ticket specific caps. Users can only create, edit non-published and read tickets.
         */
        $thing->add_cap( 'publish_support_tickets' );
        $thing->add_cap( 'edit_support_tickets' );

        /**
         *
         * attachment specific capabilities, Users can publish, delete and read their own media.
         */
        $thing->add_cap( 'upload_files' );
        $thing->add_cap( 'delete_posts' );
        $thing->add_cap( 'read' );

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
function remove_capabilities() {

    $role = get_role( 'administrator' );

    if ( $role ) {
        $role->add_cap( 'use_support' );
        $role->add_cap( 'manage_support' );
        $role->add_cap( 'manage_support_tickets' );

        $role->add_cap( 'publish_support_tickets' );

        $role->add_cap( 'edit_support_tickets' );
        $role->add_cap( 'edit_others_support_tickets' );
        $role->add_cap( 'edit_private_support_tickets' );
        $role->add_cap( 'edit_published_support_tickets' );

        $role->add_cap( 'delete_support_tickets' );
        $role->add_cap( 'delete_others_support_tickets' );
        $role->add_cap( 'delete_private_support_tickets' );
        $role->add_cap( 'delete_published_support_tickets' );

        $role->add_cap( 'read_private_support_tickets' );
    }


    $role = get_role( 'support_admin' );

    if ( $role ) {
        $role->add_cap( 'use_support' );
        $role->add_cap( 'manage_support' );
        $role->add_cap( 'manage_support_tickets' );

        $role->add_cap( 'publish_support_tickets' );

        $role->add_cap( 'edit_support_tickets' );
        $role->add_cap( 'edit_others_support_tickets' );
        $role->add_cap( 'edit_private_support_tickets' );
        $role->add_cap( 'edit_published_support_tickets' );

        $role->add_cap( 'delete_support_tickets' );
        $role->add_cap( 'delete_others_support_tickets' );
        $role->add_cap( 'delete_private_support_tickets' );
        $role->add_cap( 'delete_published_support_tickets' );

        $role->add_cap( 'read_private_support_tickets' );

        $role->add_cap( 'upload_files' );
        $role->add_cap( 'delete_posts' );
        $role->add_cap( 'read' );
    }


    $role = get_role( 'support_agent' );

    if ( $role ) {
        $role->add_cap( 'use_support' );
        $role->add_cap( 'manage_support_tickets' );

        $role->add_cap( 'publish_support_tickets' );
        $role->add_cap( 'edit_support_tickets' );

        $role->add_cap( 'upload_files' );
        $role->add_cap( 'delete_posts' );
        $role->add_cap( 'read' );
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

        $thing->remove_cap( 'use_support' );

        $thing->remove_cap( 'publish_support_tickets' );
        $thing->remove_cap( 'edit_support_tickets' );

        $thing->remove_cap( 'upload_files' );
        $thing->remove_cap( 'delete_posts' );
        $thing->remove_cap( 'read' );

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
 * @param mixed $user The user to get.
 *
 * @since 1.0.0
 * @return false|\WP_User
 */
function get_user( $user = null ) {

    if ( is_null( $user ) ) {
        $user = wp_get_current_user();
    } else if ( is_numeric( $user ) ) {
        $user = get_userdata( absint( $user ) );
    }

    // Make sure we have a valid support user
    return $user->has_cap( 'use_support' ) ? $user : false;

}


/**
 * Create a post draft for the user when they navigate to the create ticket page.
 *
 * @action template_redirect
 *
 * @since 1.5.1
 * @return void
 */
function create_user_draft_ticket() {

    if ( !get_user_draft_ticket() ) {
        $user = get_current_user_id();

        $data = array(
            'post_author' => $user,
            'post_type'   => 'support_ticket',
            'post_status' => 'draft'
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
 * @since 1.5.1
 * @return \WP_Post|false
 */
function get_user_draft_ticket() {

    $draft = get_post( get_user_meta( get_current_user_id(), 'draft_ticket', true ) );

    if ( $draft && $draft->post_status == 'draft' ) {
        return $draft;
    }

    return false;

}
