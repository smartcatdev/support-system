<?php

namespace  SmartcatSupport\util {

    use SmartcatSupport\descriptor\Option;
    use SmartcatSupport\Plugin;

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

    function extract_tags( $str, $open, $close ) {
        $matches = array();
        $regex = $pattern =  '~' . preg_quote( $open ) . '(.+)' . preg_quote( $close) . '~misU';

        preg_match_all( $regex, $str, $matches );

        return empty( $matches ) ? false : $matches[1];
    }

    function encode_code_blocks( $str ) {
        $blocks = extract_tags( $str, '<code>', '</code>' );

        foreach( $blocks as $block ) {
            $str = str_replace( $block, htmlentities( $block ), $str );
        }

        return $str;
    }

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
            'closed'            => __( 'Closed', \SmartcatSupport\PLUGIN_ID ),
        );
    }

    function filter_defaults() {
        $defaults = array(
            'status' => array(
                'new'               => true,
                'waiting'           => true,
                'opened'            => true,
                'responded'         => true,
                'needs_attention'   => true,
                'closed'            => true
            )
        );

        if( current_user_can( 'manage_support_tickets' ) ) {
            $defaults['status']['closed'] = false;
        }

        return $defaults;
    }

    function products () {
        $plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
        $products = array();

        if( get_option( Option::ECOMMERCE, Option\Defaults::ECOMMERCE ) ) {
            $post_type = array();

            if ( $plugin->woo_active ) {
                $post_type[] = 'product';
            }

            if ( $plugin->edd_active ) {
                $post_type[] = 'download';
            }

            if( !empty( $post_type ) ) {
                $query = new \WP_Query(
                    array(
                        'post_type' => $post_type,
                        'post_status' => 'publish'
                    )
                );

                foreach ($query->posts as $post) {
                    $products[$post->ID] = $post->post_title;
                }
            }
        }

        return $products;
    }

    function ecommerce_enabled( $strict = true ) {
        $plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
        $enabled = false;

        if( get_option( Option::ECOMMERCE, Option\Defaults::ECOMMERCE == 'on' ) ) {
            if( $strict && ( $plugin->woo_active || $plugin->edd_active ) ) {
                $enabled = true;
            } else {
                $enabled = true;
            }
        }

        return $enabled;
    }

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
            'support_user'  => __( 'Support User', \SmartcatSupport\PLUGIN_ID ),
        );
    }

    function add_caps( $role, $privilege = '' ) {
        $role = get_role( $role );

        if( !empty( $role ) ) {
            switch( $privilege ) {
                case 'manage':
                    $role->add_cap( 'create_support_tickets' );
                    $role->add_cap( 'use_support' );
                    $role->add_cap( 'manage_support_tickets' );
                    $role->add_cap( 'edit_support_ticket_comments' );

                    break;

                case 'admin':
                    $role->add_cap( 'create_support_tickets' );
                    $role->add_cap( 'use_support' );
                    $role->add_cap( 'manage_support_tickets' );
                    $role->add_cap( 'edit_support_ticket_comments' );
                    $role->add_cap( 'manage_support' );

                    break;

                default:
                    $role->add_cap( 'create_support_tickets' );
                    $role->add_cap( 'use_support' );

                    break;
            }
        }
    }

    function remove_caps( $role ) {
        $role = get_role( $role );

        if( !empty( $role ) ) {
            $role->remove_cap( 'create_support_tickets' );
            $role->remove_cap( 'use_support' );
            $role->remove_cap( 'manage_support_tickets' );
            $role->remove_cap( 'edit_support_ticket_comments' );
            $role->remove_cap( 'manage_support' );
        }
    }

    function get_attachments( $ticket, $orderby = 'post_date', $order = 'DESC' ) {
        $query = new \WP_Query(
            array(
                'post_parent' => $ticket->ID,
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'orderby' => $order,
                'order' => $order
            ) );

        return $query->posts;
    }
}

namespace proc {

    function create_email_templates() {
        //TODO find a better way to setup templates
    }

    function configure_roles() {
        //TODO move this here from Plugin.php
    }

    function cleanup_roles() {
        //TODO move this here from Plugin.php
    }
}