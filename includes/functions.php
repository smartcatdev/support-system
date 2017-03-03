<?php

namespace  SmartcatSupport\util {
    function just_now( $stamp ) {
        $now = date_create();
        $date = date_create( $stamp );

        if( $now->diff( $date )->format( '%i' ) == 0 ) {
            $out = __( 'Just Now', \SmartcatSupport\PLUGIN_ID );
        } else {
            $out = __( human_time_diff( strtotime( $stamp ), current_time( 'timestamp' ) ) . ' ago', \SmartcatSupport\PLUGIN_ID );
        }

        return $out;
    }

    function extract_tags( $str ) {
        $regex = '#<code\>(.+?)\<\/code\>#';
        $matches = array();

        preg_match_all( $regex, $str, $matches );

        return empty( $matches ) ? false : $matches[1];
    }

    function encode_code_blocks( $str ) {
        $blocks = extract_tags( $str );

        foreach( $blocks as $block ) {
            $str = str_replace( $block, htmlentities( $block ), $str );
        }

        return $str;
    }
}

namespace SmartcatSupport\util\ticket {

    use SmartcatSupport\descriptor\Option;
    use SmartcatSupport\Plugin;

    function author_email( $ticket ) {
        return get_user_by( 'ID', $ticket->post_author )->user_email;
    }

    function priorities () {
        return array(
            __( 'Low', \SmartcatSupport\PLUGIN_ID ),
            __( 'Medium', \SmartcatSupport\PLUGIN_ID ),
            __( 'High', \SmartcatSupport\PLUGIN_ID )
        );
    }

    function statuses () {
        return array(
            'new'               => __( 'New', \SmartcatSupport\PLUGIN_ID ),
            'waiting'           => __( 'Waiting', \SmartcatSupport\PLUGIN_ID ),
            'opened'            => __( 'Opened', \SmartcatSupport\PLUGIN_ID ),
            'responded'         => __( 'Responded', \SmartcatSupport\PLUGIN_ID ),
            'needs_attention'   => __( 'Needs Attention', \SmartcatSupport\PLUGIN_ID ),
            'resolved'          => __( 'Resolved', \SmartcatSupport\PLUGIN_ID ),
            'closed'            => __( 'Closed', \SmartcatSupport\PLUGIN_ID ),
        );
    }

    function products () {
        $plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
        $products = array();

        if( get_option( Option::ECOMMERCE_INTEGRATION, Option\Defaults::ECOMMERCE_INTEGRATION ) ) {
            $post_type = array();

            if ( $plugin->woo_active ) {
                $post_type[] = 'product';
            }

            if ( $plugin->edd_active ) {
                $post_type[] = 'download';
            }

            $query = new \WP_Query(
                array(
                    'post_type'   => $post_type,
                    'post_status' => 'publish'
                )
            );

            foreach( $query->posts as $post ) {
                $products[ $post->ID ] = $post->post_title;
            }
        }

        return $products;
    }

    function ecommerce_enabled() {
        $plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
        $enabled = false;

        if( get_option( Option::ECOMMERCE_INTEGRATION, Option\Defaults::ECOMMERCE_INTEGRATION ) ) {
            if( $plugin->woo_active || $plugin->edd_active ) {
                $enabled = true;
            }
        }

        return $enabled;
    }

}

namespace SmartcatSupport\util\user {

    function list_agents() {
        $users = get_users();
        $agents = array();

        foreach( $users as $user ) {
            if( $user->has_cap( 'manage_support_tickets' ) ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }

        return $agents;
    }

    function roles() {
        return array(
            'support_admin' => __( 'Support Admin', \SmartcatSupport\PLUGIN_ID ),
            'support_agent' => __( 'Support Agent', \SmartcatSupport\PLUGIN_ID ),
            'support_user'  => __( 'Support User', \SmartcatSupport\PLUGIN_ID )
        );
    }

    function priv_roles() {
        return array(
            'support_admin' => __( 'Support Admin', \SmartcatSupport\PLUGIN_ID ),
            'administrator' => __( 'Administrator', \SmartcatSupport\PLUGIN_ID )
        );
    }

    function append_role_caps( \WP_Role $role ) {
        $role->add_cap( 'create_support_tickets' );
        $role->add_cap( 'use_support' );
    }

    function remove_role_caps( \WP_Role $role ) {
        $role->remove_cap( 'create_support_tickets' );
        $role->remove_cap( 'use_support' );
    }

    function append_priv_role_caps( \WP_Role $role ) {
        append_role_caps( $role );

        $role->add_cap( 'manage_support_tickets' );
        $role->add_cap( 'edit_support_ticket_comments' );
    }

    function remove_priv_role_caps( \WP_Role $role ) {
        remove_role_caps( $role );

        $role->remove_cap( 'manage_support_tickets' );
        $role->remove_cap( 'edit_support_ticket_comments' );
    }

}
