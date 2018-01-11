<?php

namespace ucare;

/**
 * Custom filter output of human_time_diff().
 *
 * @param $since
 * @param $diff
 *
 * @since 1.4.2
 * @deprecated
 * @return string
 */
function filter_human_time_diff( $since, $diff ) {

    if ( $diff < 60 ) {
        $since = __( 'Seconds ago', 'ucare' );
    } else if ( $diff === 0 ) {
        $since = __( 'Just now', 'ucare' );
    } else {
        $since = sprintf( __( '%s ago', 'ucare' ), $since );
    }

    return $since;

}

/**
 * @param        $name
 * @param        $options
 * @param string $selected
 * @param array  $attrs
 * @deprecated
 */
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



/**
 * @param $key
 * @param $value
 * @deprecated
 */
function cache_put( $key, $value ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    $plugin->$key = $value;

}

/**
 * @param $key
 * @deprecated
 */
function cache_delete( $key ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    unset( $plugin->$key );

}


/**
 * @param $key
 * @param bool $default
 *
 * @return bool
 * @deprecated
 */
function cache_get( $key, $default = false ) {

    $plugin = Plugin::get_plugin( PLUGIN_ID );

    if( isset( $plugin->$key ) ) {
        return $plugin->$key;
    } else {
        return $default;
    }

}


/**
 * @return null
 * @deprecated
 */
function plugin_dir() {
    return Plugin::plugin_dir( PLUGIN_ID );
}

/**
 * @param string $path
 *
 * @return string
 * @deprecated
 */
function plugin_url( $path = '' ) {
    return trailingslashit( Plugin::plugin_url( PLUGIN_ID ) ) . ltrim( $path, '/' );
}


namespace ucare\util;

use ucare\Options;
use ucare\Plugin;


/**
 * @return array
 * @deprecated
 */
function priorities () {
    return array(
        __( 'Low', 'ucare' ),
        __( 'Medium', 'ucare' ),
        __( 'High', 'ucare' )
    );
}


/**
 * @param bool $id
 *
 * @return bool
 * @deprecated
 */
function can_use_support( $id = false ) {
    if( $id ) {

        $result = user_can( $id, 'use_support' );
    } else {
        $result = current_user_can( 'use_support' );
    }

    return $result;
}

/**
 * @param bool $id
 *
 * @return bool
 * @deprecated
 */
function can_manage_tickets( $id = false ) {
    if( $id ) {
        $result = user_can( $id, 'manage_support_tickets' );
    } else {
        $result = current_user_can( 'manage_support_tickets' );
    }

    return $result;
}

/**
 * @param bool $id
 *
 * @return bool
 * @deprecated
 */
function can_manage_support( $id = false ) {
    if( $id ) {
        $result = user_can( $id, 'manage_support' );
    } else {
        $result = current_user_can( 'manage_support' );
    }

    return $result;
}


/**
 * @return array
 * @deprecated
 */
function roles() {
    return array(
        'support_admin' => __( 'Support Admin', 'ucare' ),
        'support_agent' => __( 'Support Agent', 'ucare' ),
        'support_user'  => __( 'Support User', 'ucare' ),
    );
}

/**
 * @param        $role
 * @param string $privilege
 * @deprecated
 */
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

/**
 * @param $role
 * @deprecated
 */
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


/**
 * @deprecated
 * @return array
 */
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


/**
 * @param $user
 *
 * @return string|void
 * @deprecated
 */
function user_full_name( $user ) {

    if( $user ) {
        return $user->first_name . ' ' . $user->last_name;
    }

    return;

}


/**
 * @param $ticket
 * @param string $orderby
 * @param string $order
 * @param string $mime_type
 *
 * @return array
 * @deprecated
 */
function get_attachments( $ticket, $orderby = 'post_date', $order = 'DESC', $mime_type = '' ) {
    $query = new \WP_Query(
        array(
            'post_parent'       => $ticket->ID,
            'post_type'         => 'attachment',
            'post_status'       => 'inherit',
            'orderby'           => $order,
            'order'             => $order,
            'post_mime_type'    => $mime_type
        ) );

    return $query->posts;
}


/**
 * @param $stamp
 *
 * @return mixed|string|void
 * @deprecated
 */
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


/**
 * @param $template
 * @param array $data
 *
 * @return string
 * @deprecated
 */
function render( $template, array $data = array() ) {
    extract($data);
    ob_start();

    include($template);

    return ob_get_clean();
}


/**
 * @deprecated
 */
function author_email( $ticket ) {

    $user = get_user_by( 'ID', $ticket->post_author );

    if( $user ) {

        return $user->user_email;

    }

    return;

}


/**
 * @deprecated
 */
function products() {
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
    $products = array();

    if( get_option( Options::ECOMMERCE, \ucare\Defaults::ECOMMERCE ) ) {
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


/**
 * @param bool $strict
 *
 * @return bool
 * @deprecated
 */
function ecommerce_enabled( $strict = true ) {
    $plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
    $enabled = false;

    if( get_option( Options::ECOMMERCE, \ucare\Defaults::ECOMMERCE == 'on' ) ) {
        if( $strict && ( $plugin->woo_active || $plugin->edd_active ) ) {
            $enabled = true;
        } else {
            $enabled = true;
        }
    }

    return $enabled;
}


namespace ucare\proc;

/**
 * @deprecated
 */
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


/**
 * @deprecated
 */
function clear_scheduled_jobs() {
    wp_clear_scheduled_hook( 'ucare_cron_stale_tickets' );
    wp_clear_scheduled_hook( 'ucare_cron_close_tickets' );
    wp_clear_scheduled_hook( 'ucare_check_extension_licenses' );
}



/**
 * @param $hex
 *
 * @return array
 * @deprecated
 */
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

/**
 * @deprecated
 */
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

/**
 * @deprecated
 */
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