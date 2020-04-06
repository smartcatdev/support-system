<?php

namespace ucare;

/**
 * Sanitize truthy values such as 'on', 'yes', 1, true and anything not null.
 *
 * @param mixed $val
 *
 * @deprecated
 * @since 1.0.0
 * @return mixed
 */
function sanitize_boolean( $val ) {
    return filter_var( $val, FILTER_VALIDATE_BOOLEAN ) == true ? $val : false;
}

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
        $out = human_time_diff( strtotime( $stamp ), current_time( 'timestamp' ) ) . __( ' ago', 'ucare' );
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

use ucare\Options;

/**
 * @deprecated
 */
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

/**
 * @deprecated
 */
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

/**
 * Output a login form for the support page.
 *
 * @param array $args
 * @param bool  $echo
 *
 * @deprecated 1.7.0
 * @since 1.4.2
 * @return string
 */
function support_login_form( $args = array(), $echo = true ) {
    $defaults = array(
        'form_id'              => 'loginform',
        'form_class'           => 'support-login-form',
        'form_title'           => __( 'Support Login', 'ucare' ),
        'show_pw_reset_link'   => true,
        'show_register_link'   => true,
        'logged_in_link_text'  => __( 'Get Support', 'ucare' ),
        'pw_reset_link_text'   => __( 'Forgot Password', 'ucare' ),
        'register_link_text'   => __( 'Register', 'ucare' ),

        'label_password'       => __( 'Password', 'ucare' ),
        'label_username'       => __( 'Username or Email Address', 'ucare' ),
        'label_remember'       => __( 'Remember Me', 'ucare' ),
        'label_log_in'         => __( 'Login', 'ucare' ),

        'id_username'          => 'user_login',
        'id_password'          => 'user_pass',
        'id_remember'          => 'rememberme',
        'id_submit'            => 'wp-submit',

        'value_username'       => '',
        'value_remember'       => false
    );
    $output = \ucare\buffer_template( '_shortcode-login', shortcode_atts( $defaults, $args, 'support-login' ) );

    if ( $echo ) {
        echo $output;
    }

    return $output;
}
//add_shortcode( 'support-login', 'ucare\support_login_form' );


/**
 * Duplicate WordPress login form output.
 *
 * @filter login_form_middle
 *
 * @param $content
 * @param $args
 *
 * @deprecated 1.7.0
 * @since 1.6.0
 * @return string
 */
function call_login_form( $content, $args ) {
    if ( $args['form_id'] == 'support_login' ) {
        ob_start();
        do_action( 'login_form' );
        $content .= ob_get_clean();
    }
    return $content;
}
add_filter( 'login_form_middle', 'ucare\call_login_form', 10, 2 );


/**
 * Add a registration button to the login form.
 *
 * @filter login_form_bottom
 *
 * @param $content
 * @param $args
 *
 * @deprecated 1.7.0
 * @since 1.4.2
 * @return string
 */
function add_login_registration_button( $content, $args ) {
    if ( $args['form_id'] == 'support_login' &&
         get_option( Options::ALLOW_SIGNUPS, \ucare\Defaults::ALLOW_SIGNUPS ) &&

         // Bypass check fif not passed in args
         ( !isset( $args['show_register_link'] ) || $args['show_register_link'] == true ) ) {

        $link_text = isset( $args['register_link_text'] ) ? $args['register_link_text'] : __( 'Register', 'ucare' );
        $content .= sprintf(
            '<p class="login-register"><a class="button button-primary" href="%1$s">%2$s</a></p>',
            esc_url( \ucare\login_page_url( '?register=true' ) ),
            esc_html( $link_text )
        );
    }
    return $content;
}
add_action( 'login_form_bottom', 'ucare\add_login_registration_button', 10, 2 );



/**
 * Add a context input field to the login form.
 *
 * @filter login_form_bottom
 *
 * @param $content
 * @param $args
 *
 * @deprecated 1.7.0
 * @since 1.4.2
 * @return string
 */
function add_support_login_field( $content, $args ) {
    if ( $args['form_id'] == 'support_login' ) {
        $content .= '<input type="hidden" name="support_login_form" />';
    }
    return $content;
}
add_action( 'login_form_bottom', 'ucare\add_support_login_field', 10, 2 );