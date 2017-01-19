<?php

namespace SmartcatSupport\util;

use SmartcatSupport\Plugin;

class UserUtils {
    private function __construct() {}

    public static function list_agents( array $agents = array() ) {
        $users = get_users();

        foreach( $users as $user ) {
            if( $user->has_cap( 'edit_others_tickets' ) ) {
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
            'support_admin' => __( 'Support Admin', Plugin::ID ),
            'support_agent', __( 'Support Agent', Plugin::ID ),
            'support_user', __( 'Support User', Plugin::ID )
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
            $caps[] = 'edit_comments';
        }

        return $caps;
    }
}