<?php

function support_system_agents() {
    $agents = [];

    $users = get_users( [ 'role' => [ 'support_agent' ] ] );

    if( $users != null ) {
        foreach( $users as $user ) {
            $agents[ $user->ID ] = $user->display_name;
        }
    }

    return $agents;
}


