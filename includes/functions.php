<?php

namespace ucare;

use ucare\descriptor\Option;
use ucare\util\Logger;


function url() {
    return get_the_permalink( get_option( Option::TEMPLATE_PAGE_ID ) );
}

function plugin_dir() {
    return Plugin::plugin_dir( \ucare\PLUGIN_ID );
}

function plugin_url() {
    return Plugin::plugin_url( \ucare\PLUGIN_ID );
}

function in_dev_mode() {
    return get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on';
}

function enqueue_admin_scripts( $hook ) {

    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );

    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_script( 'wp_media_uploader',
        $plugin->url() . 'assets/lib/wp_media_uploader.js', array( 'jquery' ), $plugin->version() );

    wp_enqueue_style( 'support-admin-icons',
        $plugin->url() . '/assets/icons/style.css', null,$plugin->version() );

    wp_register_script('support-admin-js',
        $plugin->url() . 'assets/admin/admin.js', array( 'jquery' ), $plugin->version() );

    wp_localize_script( 'support-admin-js',
        'SupportSystem', array(
            'ajax_url'   => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'support_ajax' )
        )
    );

    wp_enqueue_script( 'support-admin-js' );

    wp_enqueue_style( 'support-admin-css',
        $plugin->url() . '/assets/admin/admin.css', null, $plugin->version() );

    if( strpos( $hook, 'ucare' ) !== false ) {

        wp_enqueue_script( 'moment',
            $plugin->url() . '/assets/lib/moment/moment.min.js', null, $plugin->version() );

        wp_enqueue_script( 'flot',
            $plugin->url() . '/assets/lib/flot/jquery.flot.min.js', null, $plugin->version() );

        wp_enqueue_script( 'flot-time',
            $plugin->url() . '/assets/lib/flot/jquery.flot.time.min.js', null, $plugin->version() );

        wp_enqueue_script( 'flot-resize',
            $plugin->url() . '/assets/lib/flot/jquery.flot.resize.min.js', null, $plugin->version() );

        wp_enqueue_script( 'moment',
            $plugin->url() . '/assets/lib/moment/moment.min.js', null, $plugin->version() );

        wp_enqueue_script( 'ucare-reports-js',
            $plugin->url() . '/assets/admin/reports.js', null, $plugin->version() );

        wp_enqueue_style( 'ucare-reports-css',
            $plugin->url() . '/assets/admin/reports.css', null, $plugin->version() );

    }

    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_script( 'wp_media_uploader',
        $plugin->url() . 'assets/lib/wp_media_uploader.js', array( 'jquery' ), $plugin->version() );

    wp_enqueue_style( 'support-admin-icons',
        $plugin->url() . '/assets/icons/style.css', null,$plugin->version() );

    wp_register_script('support-admin-js',
        $plugin->url() . 'assets/admin/admin.js', array( 'jquery' ), $plugin->version() );

    wp_localize_script( 'support-admin-js',
        'SupportSystem', array(
            'ajax_url'   => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'support_ajax' )
        )
    );

    wp_enqueue_media();
    wp_enqueue_script( 'support-admin-js' );

    wp_enqueue_style( 'support-admin-css',
        $plugin->url() . '/assets/admin/admin.css', null, $plugin->version() );

}

function admin_page_header() {
    include_once \ucare\plugin_dir() . '/templates/admin-header.php';
}

function admin_page_sidebar() {
    include_once \ucare\plugin_dir() . '/templates/admin-sidebar.php';
}

function ticket_updated( $null, $id, $key, $value ) {

    global $wpdb;

    if( get_post_type( $id ) == 'support_ticket' && $key == 'status' ) {

        $q = "UPDATE {$wpdb->posts}
              SET post_modified = %s, post_modified_gmt = %s
              WHERE ID = %d ";

        $q = $wpdb->prepare( $q, array( current_time( 'mysql' ), current_time( 'mysql', 1 ), $id ) );

        $wpdb->query( $q );

        delete_post_meta( $id, 'stale' );

        if( $value == 'closed' ) {

            update_post_meta( $id, 'closed_date', current_time( 'mysql' ) );
            update_post_meta( $id, 'closed_by', wp_get_current_user()->ID );

        }

    }
}

function comment_save( $id ) {

    $post = get_post( get_comment( $id )->comment_post_ID );

    if( $post->post_type == 'support_ticket' ) {

        $status = get_post_meta( $post->ID, 'status', true );

        // Don't update the status if the ticket has already been closed
        if( $status != 'closed' ) {

            // If the user is an agent or admin
            if( current_user_can( 'manage_support_tickets' ) ) {

                update_post_meta( $post->ID, 'status', 'waiting' );

                // If the status is new, overwrite it to clear stale values else set status to responded
            } else {
                update_post_meta( $post->ID, 'status', $status == 'new' ? 'new' : 'responded' );
            }

        }

    }

}

function mark_stale_tickets() {

    $logger = new Logger( 'cron' );

    // Calculate max age as n days
    $max_age = get_option( Option::INACTIVE_MAX_AGE, Option\Defaults::INACTIVE_MAX_AGE );

    // Current server time
    $time = current_time( 'timestamp', 1 );

    // Get the GMT date for n days ago
    $date = $time - ( 60 * 60 * 24 * $max_age );

    // The date when the ticket will be considered expired
    $expires = $time + ( 60 * 60 * 24 );

    $q = new \WP_Query( array(
        'posts_per_page' => -1,
        'post_type'      => 'support_ticket',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'stale',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key'     => 'status',
                'value'   => 'closed',
                'compare' => '!='
            )
        ),
        'date_query'     => array(
            array(
                'before'    => date( 'Y-m-d 23:59:59', $date ),
                'column'    => 'post_modified_gmt'
            )
        )
    ) );

    $logger->i( $q->post_count . _n( ' ticket', ' tickets', $q->post_count ) . _n( ' has', ' have', $q->post_count ) . '  been marked stale' );

    foreach( $q->posts as $ticket ) {

        // Mark the post as stale
        add_post_meta( $ticket->ID, 'stale', date( 'Y-m-d H:i:s', $expires ) );

        // Fire an action to handle ticket going stale
        do_action( 'support_mark_ticket_stale', $ticket );

    }

}

function close_stale_tickets() {

    $logger = new Logger( 'cron' );

    if( get_option( Option::AUTO_CLOSE ) === 'on' ) {

        // Get all stale tickets
        $q = new \WP_Query( array(
            'posts_per_page' => -1,
            'post_type'      => 'support_ticket',
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => 'stale',
                    'value'   => current_time( 'mysql', 1 ),
                    'type'    => 'DATETIME',
                    'compare' => '<='
                ),
                array(
                    'key'     => 'status',
                    'value'   => 'waiting'
                )
            )
        ) );

        $logger->i( $q->post_count . _n( ' ticket', ' tickets', $q->post_count ) . _n( ' has', ' have', $q->post_count ) . ' been automatically closed' );

        foreach( $q->posts as $ticket ) {

            // Mark the ticket as closed and delete stale status
            update_post_meta( $ticket->ID, 'status', 'closed' );

            // overwrite the user ID to nobody
            update_post_meta( $ticket->ID, 'closed_by', -1 );
            delete_post_meta( $ticket->ID, 'stale' );

            // Fire an action to handle ticket going stale
            do_action( 'support_autoclose_ticket', $ticket );

        }
    } else {

        $logger->i( 'Ticket auto-closing is disabled, please re-enable if you wish for tickets to be closed automatically' );

    }

}



namespace ucare\util;

use ucare\descriptor\Option;
use ucare\Plugin;

function render( $template, array $data = array() ) {
    extract($data);
    ob_start();

    include($template);

    return ob_get_clean();
}

function user_full_name( $user ) {
    return $user->first_name . ' ' . $user->last_name;

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
        $out = __( 'Just Now', 'ucare' );
    } else {
        $out = __( human_time_diff( strtotime( $stamp ), current_time( 'timestamp' ) ) . ' ago', 'ucare' );
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
        $str = str_replace( $block, trim(  htmlentities( $block ) ), $str );
    }

    return $str;
}

function author_email( $ticket ) {
    return get_user_by( 'ID', $ticket->post_author )->user_email;
}

function priorities () {
    return array(
        __( 'Low', 'ucare' ),
        __( 'Medium', 'ucare' ),
        __( 'High', 'ucare' )
    );
}

function statuses () {
    return array(
        'new'               => __( 'New', 'ucare' ),
        'waiting'           => __( 'Waiting', 'ucare' ),
        'opened'            => __( 'Opened', 'ucare' ),
        'responded'         => __( 'Responded', 'ucare' ),
        'needs_attention'   => __( 'Needs Attention', 'ucare' ),
        'closed'            => __( 'Closed', 'ucare' ),
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
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
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
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
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
        'support_admin' => __( 'Support Admin', 'ucare' ),
        'support_agent' => __( 'Support Agent', 'ucare' ),
        'support_user'  => __( 'Support User', 'ucare' ),
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


namespace ucare\proc;

use ucare\descriptor\Option;

function schedule_cron_jobs() {
    if ( !wp_next_scheduled( 'ucare_cron_stale_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_stale_tickets' );
    }

    if ( !wp_next_scheduled( 'ucare_cron_close_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_close_tickets' );
    }
}

function clear_scheduled_jobs() {
    wp_clear_scheduled_hook( 'ucare_cron_stale_tickets' );
    wp_clear_scheduled_hook( 'ucare_cron_close_tickets' );
}

function setup_template_page() {
    $post_id = null;
    $post = get_post( get_option( Option::TEMPLATE_PAGE_ID ) ) ;

    if( empty( $post ) ) {
        $post_id = wp_insert_post(
            array(
                'post_type' =>  'page',
                'post_status' => 'publish',
                'post_title' => __( 'Support', 'ucare' )
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
            'option' => Option::TICKET_CREATED_EMAIL,
            'subject' => __( 'You have created a new request for support', 'ucare' )
        ),
        array(
            'template' => '/emails/welcome.html',
            'option' => Option::WELCOME_EMAIL_TEMPLATE,
            'subject' => __( 'Welcome to Support', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-closed.html',
            'option' => Option::TICKET_CLOSED_EMAIL_TEMPLATE,
            'subject' => __( 'Your request for support has been closed', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-reply.html',
            'option' => Option::AGENT_REPLY_EMAIL,
            'subject' => __( 'Reply to your request for support', 'ucare' )
        ),
        array(
            'template' => '/emails/password-reset.html',
            'option' => Option::PASSWORD_RESET_EMAIL,
            'subject' => __( 'Your password has been reset', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-close-warning.html',
            'option' => Option::INACTIVE_EMAIL,
            'subject' => __( 'You have a ticket awaiting action', 'ucare' )
        )
    );

    $default_style = file_get_contents( \ucare\plugin_dir() . '/emails/default-style.css' );

    foreach( $default_templates as $config ) {
        $template = get_post( get_option( $config['option'] ) );

        if( is_null( get_post( $template ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => $config['subject'],
                    'post_content'  => file_get_contents( \ucare\plugin_dir() . $config['template'] )
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
    $administrator->add_cap( 'delete_support_tickets' );
    $administrator->add_cap( 'delete_others_support_tickets' );
    $administrator->add_cap( 'delete_private_support_tickets' );
    $administrator->add_cap( 'delete_published_support_tickets' );

    foreach( \ucare\util\roles() as $role => $name ) {
        add_role( $role, $name );
    }

    \ucare\util\add_caps( 'customer' );
    \ucare\util\add_caps( 'subscriber' );
    \ucare\util\add_caps( 'support_user' );

    \ucare\util\add_caps( 'support_agent' , 'manage' );

    \ucare\util\add_caps( 'support_admin' , 'admin' );
    \ucare\util\add_caps( 'administrator' , 'admin' );
}

function cleanup_roles() {
    foreach( \ucare\util\roles() as $role => $name ) {
        remove_role( $role );
    }

    \ucare\util\remove_caps( 'customer' );
    \ucare\util\remove_caps( 'subscriber' );
    \ucare\util\remove_caps( 'administrator' );

    $administrator = get_role( 'administrator' );

    $administrator->remove_cap( 'read_support_ticket' );
    $administrator->remove_cap( 'read_support_tickets' );
    $administrator->remove_cap( 'edit_support_ticket' );
    $administrator->remove_cap( 'edit_support_tickets' );
    $administrator->remove_cap( 'edit_others_support_tickets' );
    $administrator->remove_cap( 'edit_published_support_tickets' );
    $administrator->remove_cap( 'publish_support_tickets' );
    $administrator->remove_cap( 'delete_support_tickets' );
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



namespace ucare\statprocs;

function count_tickets( $start, $end, $args = array() ) {
    global $wpdb;

    $start = is_a( $start, 'DateTimeInterface' ) ? $start : date_create( strtotime( $start ) );
    $end =   is_a( $end, 'DateTimeInterface' )   ? $end   : date_create( strtotime( $end ) );

    if( !$start || !$end || $start > $end ) {
        return new \WP_Error( 'invalid date supplied' );
    }

    // Default count by day
    $range = "%Y-%m-%d";
    $interval = new \DateInterval( 'P1D' );
    $diff = $end->diff( $start )->format( '%a' );

    // Get monthly totals if greater than 2 months
    if ( $diff > 62 ) {
        $range = "%Y-%m";
        $interval = new \DateInterval( 'P1M' );
    }

    $values = array($range, $start->format( 'Y-m-d: 00:00:00' ), $end->format( 'Y-m-d 23:59:59' ) );

    if( !empty( $args['closed'] ) ) {

        $q = "SELECT DATE_FORMAT(DATE(m.meta_value), %s ) as d,
          COUNT(m.meta_value) as c
          FROM {$wpdb->posts} p
          INNER JOIN {$wpdb->postmeta} m 
            ON p.ID = m.post_id
          WHERE p.post_type = 'support_ticket'
            AND p.post_status = 'publish' 
            AND m.meta_key = 'closed_date'
            AND (DATE(m.meta_value) BETWEEN DATE( %s ) AND DATE( %s )) ";

    } else {

        $q = "SELECT DATE_FORMAT(DATE(p.post_date), %s ) as d,
          COUNT(p.post_date) as c
          FROM {$wpdb->posts} p
          WHERE p.post_type = 'support_ticket'
            AND p.post_status = 'publish' 
            AND (DATE(p.post_date) BETWEEN DATE( %s ) AND DATE( %s )) ";

    }

    $q .= " GROUP BY d ORDER BY d";

    // Get the data from the query
    $results = $wpdb->get_results( $wpdb->prepare( $q, $values ), ARRAY_A );
    $data = array();

    // All dates in the period at a set interval
    $dates = new \DatePeriod( $start, $interval, clone $end->modify( '+1 second' ) );

    foreach( $dates as $date ) {

        $curr = $date->format( 'Y-m-d' );

        // Set it to 0 by default for this date
        $data[ $curr ] = 0;

        // Loop through each found total
        foreach( $results as $result ) {

            // If the total's date is like the current date set it
            if( strpos( $curr, $result['d'] ) !== false ) {

                $data[ $curr ] = ( int ) $result['c'];

            }

        }

    }

    return $data;
}

function get_unclosed_tickets() {

    global $wpdb;

    $q = 'select ifnull( count(*), 0 ) from ' . $wpdb->prefix . 'posts as a '
            . 'left join ' . $wpdb->prefix . 'postmeta as b '
            . 'on a.ID = b.post_id '
            . 'where a.post_type = "support_ticket" and a.post_status = "publish" '
            . 'and b.meta_key = "status" and b.meta_value != "closed"';

    return $wpdb->get_var( $q );

}

function get_ticket_count( $args = array() ) {

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

