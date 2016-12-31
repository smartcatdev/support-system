<?php

namespace SmartcatSupport\util;

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
}