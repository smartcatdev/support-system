<?php

namespace SmartcatSupport {

    use SmartcatSupport\descriptor\Option;

    function url() {
        return get_the_permalink( get_option( Option::TEMPLATE_PAGE_ID ) );
    }

    function plugin_dir() {
        return Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID );
    }

    function plugin_url() {
        return Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );
    }

    function in_dev_mode() {
        return get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on';
    }
}

namespace  SmartcatSupport\util {

    use SmartcatSupport\descriptor\Option;
    use SmartcatSupport\Plugin;


    function user_full_name( $user ) {
        return $user->first_name . ' ' . $user->last_name;
    }

    function admin_notice( $message, $class ) {
        add_action( 'admin_notices', function () use ( $message, $class ) {
            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( implode( ' ', $class ) ), $message );
        } );
    }

    function can_use_support( $id = false ) {
        if( $id ) {

            $result = user_can( $id, 'use_support' );
        } else {
            $result = current_user_can( 'use_support' );
        }

        return $result;
    }

    function can_manage_tickets( $id = false ) {
        if( $id ) {
            $result = user_can( $id, 'manage_support_tickets' );
        } else {
            $result = current_user_can( 'manage_support_tickets' );
        }

        return $result;
    }

    function can_manage_support( $id = false ) {
        if( $id ) {
            $result = user_can( $id, 'manage_support' );
        } else {
            $result = current_user_can( 'manage_support' );
        }

        return $result;
    }

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
            
            $post_type = implode('","', $post_type );
            
            if( !empty( $post_type ) ) {

                global $wpdb;
                
                $query = 'select ID from ' . $wpdb->prefix . 'posts where post_type in ("' . $post_type . '") and post_status = "publish"';
                
                $posts = $wpdb->get_results( $query );
                
                foreach( $posts as $post ) {
                    
                    $products[ $post->ID ] = get_the_title( $post->ID );
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
                'post_parent'       => $ticket->ID,
                'post_type'         => 'attachment',
                'post_mime_type'    => 'image',
                'post_status'       => 'inherit',
                'orderby'           => $order,
                'order'             => $order
            ) );

        return $query->posts;
    }
}

namespace SmartcatSupport\proc {

    use SmartcatSupport\descriptor\Option;

    function setup_template_page() {
        $post_id = null;
        $post = get_post( get_option( Option::TEMPLATE_PAGE_ID ) ) ;

        if( empty( $post ) ) {
            $post_id = wp_insert_post(
                array(
                    'post_type' =>  'page',
                    'post_status' => 'publish',
                    'post_title' => __( 'Support', PLUGIN_ID )
                )
            );
        } else if( $post->post_status == 'trash' ) {
            wp_untrash_post( $post->ID );

            $post_id = $post->ID;
        } else {
            $post_id = $post->ID;
        }

        if( !empty( $post_id ) ) {
            update_option( Option::TEMPLATE_PAGE_ID, $post_id );
        }
    }

    function create_email_templates() {

        $default_templates = array(
            array(
                'template' => '/emails/ticket-created.html',
                'option' => Option::CREATED_EMAIL_TEMPLATE,
                'subject' => __( 'You have created a new request for support', \SmartcatSupport\PLUGIN_ID )
            ),
            array(
                'template' => '/emails/welcome.html',
                'option' => Option::WELCOME_EMAIL_TEMPLATE,
                'subject' => __( 'Welcome to Support', \SmartcatSupport\PLUGIN_ID )
            ),
            array(
                'template' => '/emails/ticket-closed.html',
                'option' => Option::TICKET_CLOSED_EMAIL_TEMPLATE,
                'subject' => __( 'Your request for support has been closed', \SmartcatSupport\PLUGIN_ID )
            ),
            array(
                'template' => '/emails/ticket-reply.html',
                'option' => Option::REPLY_EMAIL_TEMPLATE,
                'subject' => __( 'Reply to your request for support', \SmartcatSupport\PLUGIN_ID )
            ),
            array(
                'template' => '/emails/password-reset.html',
                'option' => Option::PASSWORD_RESET_EMAIL,
                'subject' => __( 'Your password has been reset', \SmartcatSupport\PLUGIN_ID )
            )
        );

        $default_style = file_get_contents( \SmartcatSupport\plugin_dir() . '/emails/default-style.css' );

        foreach( $default_templates as $config ) {
            $template = get_post( get_option( $config['option'] ) );

            if( is_null( get_post( $template ) ) ) {
                $id = wp_insert_post(
                    array(
                        'post_type'     => 'email_template',
                        'post_status'   => 'publish',
                        'post_title'    => $config['subject'],
                        'post_content'  => file_get_contents( \SmartcatSupport\plugin_dir() . $config['template'] )
                    )
                );

                if( !empty( $id ) ) {
                    update_post_meta( $id, 'styles', $default_style );
                    update_option( $config['option'], $id );
                }
            } else {
                wp_untrash_post( $template );
            }
        }
    }

    function configure_roles() {
        $administrator = get_role( 'administrator' );

        $administrator->add_cap( 'read_support_ticket' );
        $administrator->add_cap( 'read_support_tickets' );
        $administrator->add_cap( 'edit_support_ticket' );
        $administrator->add_cap( 'edit_support_tickets' );
        $administrator->add_cap( 'edit_others_support_tickets' );
        $administrator->add_cap( 'edit_published_support_tickets' );
        $administrator->add_cap( 'publish_support_tickets' );
        $administrator->add_cap( 'delete_others_support_tickets' );
        $administrator->add_cap( 'delete_private_support_tickets' );
        $administrator->add_cap( 'delete_published_support_tickets' );

        foreach( \SmartcatSupport\util\roles() as $role => $name ) {
            add_role( $role, $name );
        }

        \SmartcatSupport\util\add_caps( 'customer' );
        \SmartcatSupport\util\add_caps( 'subscriber' );
        \SmartcatSupport\util\add_caps( 'support_user' );

        \SmartcatSupport\util\add_caps( 'support_agent' , 'manage' );

        \SmartcatSupport\util\add_caps( 'support_admin' , 'admin' );
        \SmartcatSupport\util\add_caps( 'administrator' , 'admin' );
    }

    function cleanup_roles() {
        foreach( \SmartcatSupport\util\roles() as $role => $name ) {
            remove_role( $role );
        }

        \SmartcatSupport\util\remove_caps( 'customer' );
        \SmartcatSupport\util\remove_caps( 'subscriber' );
        \SmartcatSupport\util\remove_caps( 'administrator' );

        $administrator = get_role( 'administrator' );

        $administrator->remove_cap( 'read_support_ticket' );
        $administrator->remove_cap( 'read_support_tickets' );
        $administrator->remove_cap( 'edit_support_ticket' );
        $administrator->remove_cap( 'edit_support_tickets' );
        $administrator->remove_cap( 'edit_others_support_tickets' );
        $administrator->remove_cap( 'edit_published_support_tickets' );
        $administrator->remove_cap( 'publish_support_tickets' );
        $administrator->remove_cap( 'delete_others_support_tickets' );
        $administrator->remove_cap( 'delete_private_support_tickets' );
        $administrator->remove_cap( 'delete_published_support_tickets' );
    }
    
    function hex2rgb( $hex ) {
        $hex = str_replace( "#", "", $hex );

        if ( strlen( $hex ) == 3 ) {
            $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
            $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }
        $rgb = array ( $r, $g, $b );
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }
    
}

namespace SmartcatSupport\statprocs {
    
    function get_unclosed_tickets() {
        
        global $wpdb;
        
        $q = 'select ifnull( count(*), 0 ) from ' . $wpdb->prefix . 'posts as a '
                . 'left join ' . $wpdb->prefix . 'postmeta as b '
                . 'on a.ID = b.post_id '
                . 'where a.post_type = "support_ticket" and a.post_status = "publish" '
                . 'and b.meta_key = "status" and b.meta_value != "closed"';
        
        return $wpdb->get_var( $q );
        
    }
    
    function get_ticket_count( $args ) {
        
        global $wpdb;
        
        $args['status'] = isset( $args['status'] ) ? $args['status'] : null; 
        $args['priority'] = isset( $args['priority'] ) ? $args['priority'] : null; 
        $args['agent'] = isset( $args['agent'] ) ? $args['agent'] : null; 
        
        
        $q = 'select ifnull( count(*), 0 ) from ' . $wpdb->prefix . 'posts as a '
                . 'left join ' . $wpdb->prefix . 'postmeta as b '
                . 'on a.ID = b.post_id '
                . 'where a.post_type = "support_ticket" and a.post_status = "publish"';
        
        if( $args['status'] ) {
            $q .= ' and b.meta_key = "status" and b.meta_value in ("'. $args['status'] . '")';
        }
        
        if( $args['priority'] ) {
            $q .= ' and b.meta_key = "priority" and b.meta_value in ("'. $args['priority'] . '")';
        }
        
        if( $args['agent'] ) {
            $q .= ' and b.meta_key = "agent" and b.meta_value in ("'. $args['agent'] . '")';
        }
        
        return $wpdb->get_var( $q );
        
    }
    
    function get_user_assigned( $agents ) {
        
        $args = array(
            'post_type'     => 'support_ticket',
            'post_status'   => 'publish',
            'meta_query'    => array(
                'relation'  => 'AND',
                array(
                    'key'       => 'agent',
                    'value'     => $agents,
                    'compare'   => 'IN'
                ),
                array(
                    'key'       => 'status',
                    'value'     => 'closed',
                    'compare'   => '!='                    
                )
            )
        );
        
        $results = new \WP_Query( $args );
        
        return $results->found_posts;
        
    }
    
}