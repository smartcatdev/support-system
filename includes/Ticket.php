<?php

namespace SmartcatSupport;

use SmartcatSupport\admin\Role;

/**
 * Description of TicketMeta
 *
 * @author ericg
 */
class Ticket {
    const POST_TYPE = 'sc_support_ticket';

    public static function agent_list() {
        $agents[ '' ] = 'No Agent Assigned';
        
        $users = get_users( [ 'role__in' => [ Role::AGENT, Role::ADMIN ] ] );
        
        if( $users != null ) {
            foreach( $users as $user ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }
        
        return $agents;
    }
    
    public static function status_list() {
        return [ 
            'new'           => 'New', 
            'in_progress'   => 'In Progress', 
            'resolved'      => 'Resolved', 
            'follow_up'     => 'Follow Up', 
            'closed'        => 'Closed'
        ]; 
    }
}
