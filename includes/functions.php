<?php

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
            'open'              => __( 'Opened', \SmartcatSupport\PLUGIN_ID ),
            'responded'         => __( 'Responded', \SmartcatSupport\PLUGIN_ID ),
            'needs_attention'   => __( 'Needs Attention', \SmartcatSupport\PLUGIN_ID ),
            'resolved'          => __( 'Resolved', \SmartcatSupport\PLUGIN_ID ),
            'closed'            => __( 'Closed', \SmartcatSupport\PLUGIN_ID ),
        );
    }

    function products () {
        $plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
        $products = array();

        if( $plugin->woo_active && get_option( Option::WOO_INTEGRATION ) == 'on' ) {
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
            );

            $query = new \WP_Query( $args );

            while( $query->have_posts() ) {
                $products[ $query->post->ID ] = $query->post->post_title;

                $query->next_post();
            }
        }

        if( $plugin->edd_active && get_option( Option::EDD_INTEGRATION ) == 'on' ) {
            $args = array(
                'post_type' => 'download',
                'post_status' => 'publish',
            );

            $query = new \WP_Query( $args );

            while( $query->have_posts() ) {
                $products[ $query->post->ID ] = $query->post->post_title;

                $query->next_post();
            }
        }

        return $products;
    }

}

namespace SmartcatSupport\util\user {

    function list_agents() {
        $users = get_users();
        $agents = array();

        foreach( $users as $user ) {

            if( $user->has_cap( 'edit_others_tickets' ) ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }

        return $agents;
    }

    function roles() {
        return array(
            'support_admin' => __( 'Support Admin', \SmartcatSupport\PLUGIN_ID ),
            'support_agent', __( 'Support Agent', \SmartcatSupport\PLUGIN_ID ),
            'support_user', __( 'Support User', \SmartcatSupport\PLUGIN_ID )
        );
    }

    function append_role_caps( \WP_Role $role ) {
        $role->add_cap( 'create_support_tickets' );
        $role->add_cap( 'view_support_tickets' );
        $role->add_cap( 'comment_on_support_tickets' );
        $role->add_cap( 'unfiltered_html' );
    }

    function remove_role_caps( \WP_Role $role ) {
        $role->remove_cap( 'create_support_tickets' );
        $role->remove_cap( 'view_support_tickets' );
        $role->remove_cap( 'comment_on_support_tickets' );
        $role->remove_cap( 'unfiltered_html' );
    }

    function append_priv_role_caps( \WP_Role $role ) {
        $role->add_cap( 'create_support_tickets' );
        $role->add_cap( 'view_support_tickets' );
        $role->add_cap( 'comment_on_support_tickets' );
        $role->add_cap( 'unfiltered_html' );
        $role->add_cap( 'manage_support_tickets' );
        $role->add_cap( 'edit_support_ticket_comments' );
    }

    function remove_priv_role_caps( \WP_Role $role ) {
        $role->remove_cap( 'create_support_tickets' );
        $role->remove_cap( 'view_support_tickets' );
        $role->remove_cap( 'comment_on_support_tickets' );
        $role->remove_cap( 'unfiltered_html' );
        $role->remove_cap( 'manage_support_tickets' );
        $role->remove_cap( 'edit_support_ticket_comments' );
    }

}
