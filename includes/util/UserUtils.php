<?php

namespace SmartcatSupport\util;

use const SmartcatSupport\PLUGIN_NAME;

class UserUtils {
    private function __construct() {}

    public static function list_agents( array $agents = array() ) {
        $users = get_users( array( 'role' => array( 'support_agent' ) ) );

        if( $users != null ) {
            foreach( $users as $user ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }

        return $agents;
    }

    public static function add_caps( \WP_Role $role, $privileged = false ) {
        foreach( self::caps( $privileged ) as $cap ) {
            $role->add_cap( $cap );
        }
    }

    public static function remove_caps( \WP_Role $role, $privileged = false ) {
        foreach( self::caps( $privileged ) as $cap ) {
            $role->remove_cap( $cap );
        }
    }

    public static function roles() {
        return array(
            'support_admin' => __( 'Support Admin', PLUGIN_NAME ),
            'support_agent', __( 'Support Agent', PLUGIN_NAME ),
            'support_user', __( 'Support User', PLUGIN_NAME )
        );
    }

    private static function caps( $privileged ) {
        $caps = array(
            'view_support_tickets',
            'create_support_tickets',
            'unfiltered_html'
        );

        if( $privileged ) {
            $caps[] = 'edit_others_tickets';
        }

        return $caps;
    }
}