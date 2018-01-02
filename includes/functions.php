<?php
/**
 * General use functions and utilities.
 *
 * @since 1.0.0
 * @package ucare
 */
namespace ucare;


/**
 * Get the support page URL.
 *
 * @param string $path
 *
 * @since 1.0.0
 * @return string
 */
function support_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::TEMPLATE_PAGE_ID ) ) . $path;
}


/**
 * Get the URL of the create ticket page.
 *
 * @param string $path
 *
 * @since 1.0.0
 * @return string
 */
function create_page_url( $path = '' ) {
    return get_the_permalink( get_option( Options::CREATE_TICKET_PAGE_ID ) ) . $path;
}


/**
 * Safely pluck a value from an object or array.
 *
 * @param object|array $obj
 * @param string       $field
 * @param mixed        $default
 *
 * @since 1.6.0
 * @return mixed
 */
function pluck( $obj, $field, $default = false ) {

    if ( empty( $obj ) ) {
        return $default;
    }

    $data = $obj;

    if ( is_object( $obj ) ) {
        $data = clone $obj;
    }

    $data = (array) $data;

    if ( isset( $data[ $field ] ) ) {
        return $data[ $field ];
    }

    return $default;

}


function allowed_mime_types( $type = null ) {

    $file_types  = explode( ',', get_option( Options::FILE_MIME_TYPES, Defaults::FILE_MIME_TYPES   ) );
    $image_types = explode( ',', get_option( Options::IMAGE_MIME_TYPES, Defaults::IMAGE_MIME_TYPES ) );

    if ( $type == 'image' ) {
        return $image_types;
    } else if ( $type == 'file' ) {
        return $file_types;
    }

    return array_merge( $file_types, $image_types );

}




function selectbox( $name, $options, $selected = '', $attrs = array() ) { ?>

    <select name="<?php esc_attr_e( $name ); ?>"

            <?php foreach ( $attrs as $attr => $values ) : ?>

                <?php echo $attr . '="' . esc_attr( $values ) . '"' ?>

            <?php endforeach; ?>>

        <?php foreach ( $options as $value => $label ) : ?>

            <option value="<?php esc_attr_e( $value ); ?>"

                <?php selected( $selected, $value ); ?> ><?php echo $label ?></option>

        <?php endforeach; ?>

    </select>

<?php }







namespace ucare\util;

use ucare\Options;
use ucare\Plugin;


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




function priorities () {
    return array(
        __( 'Low', 'ucare' ),
        __( 'Medium', 'ucare' ),
        __( 'High', 'ucare' )
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



namespace ucare\proc;

use ucare\Options;

function schedule_cron_jobs() {
    if ( !wp_next_scheduled( 'ucare_cron_stale_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_stale_tickets' );
    }

    if ( !wp_next_scheduled( 'ucare_cron_close_tickets' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_cron_close_tickets' );
    }

    if ( !wp_next_scheduled( 'ucare_check_extension_licenses' ) ) {
        wp_schedule_event( time(), 'daily', 'ucare_check_extension_licenses' );
    }
}

function clear_scheduled_jobs() {
    wp_clear_scheduled_hook( 'ucare_cron_stale_tickets' );
    wp_clear_scheduled_hook( 'ucare_cron_close_tickets' );
    wp_clear_scheduled_hook( 'ucare_check_extension_licenses' );
}

function setup_template_page() {
    $post_id = null;
    $post = get_post( get_option( Options::TEMPLATE_PAGE_ID ) ) ;

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
        update_option( Options::TEMPLATE_PAGE_ID, $post_id );
    }
}

function create_email_templates() {

    $default_templates = array(
        array(
            'template' => '/emails/ticket-created.html',
            'option' => Options::TICKET_CREATED_EMAIL,
            'subject' => __( 'You have created a new request for support', 'ucare' )
        ),
        array(
            'template' => '/emails/welcome.html',
            'option' => Options::WELCOME_EMAIL_TEMPLATE,
            'subject' => __( 'Welcome to Support', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-closed.html',
            'option' => Options::TICKET_CLOSED_EMAIL_TEMPLATE,
            'subject' => __( 'Your request for support has been closed', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-reply.html',
            'option' => Options::AGENT_REPLY_EMAIL,
            'subject' => __( 'Reply to your request for support', 'ucare' )
        ),
        array(
            'template' => '/emails/password-reset.html',
            'option' => Options::PASSWORD_RESET_EMAIL,
            'subject' => __( 'Your password has been reset', 'ucare' )
        ),
        array(
            'template' => '/emails/ticket-close-warning.html',
            'option' => Options::INACTIVE_EMAIL,
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

    $defaults = array(
        'status'   => false,
        'priority' => false,
        'agent'    => false,
        'author'   => false
    );

    $args = wp_parse_args( $args, $defaults );


    $q = 'select ifnull( count( DISTINCT a.ID ), 0 ) from ' . $wpdb->prefix . 'posts as a '
            . 'left join ' . $wpdb->prefix . 'postmeta as b '
            . 'on a.ID = b.post_id '
            . 'where a.post_type = "support_ticket" and a.post_status = "publish"';

    if ( $args['status'] ) {
        $q .= ' and b.meta_key = "status" and b.meta_value in ("'. esc_sql( $args['status'] ) . '")';
    }

    if ( $args['priority'] ) {
        $q .= ' and b.meta_key = "priority" and b.meta_value in ("'. esc_sql( $args['priority'] ) . '")';
    }

    if ( $args['agent'] ) {
        $q .= ' and b.meta_key = "agent" and b.meta_value in ("'. esc_sql( $args['agent'] ) . '")';
    }

    if ( $args['author'] ) {
        $q .= " AND a.post_author = " . absint( $args['author'] );
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

