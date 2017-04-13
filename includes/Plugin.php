<?php

namespace SmartcatSupport;

use smartcat\core\AbstractPlugin;
use smartcat\core\HookSubscriber;
use smartcat\mail\Mailer;
use SmartcatSupport\ajax\Media;
use SmartcatSupport\ajax\Ticket;
use SmartcatSupport\ajax\Comment;
use SmartcatSupport\ajax\Settings;
use SmartcatSupport\ajax\Registration;
use SmartcatSupport\component\ECommerce;
use SmartcatSupport\component\Notifications;
use SmartcatSupport\component\TicketPostType;
use SmartcatSupport\component\Hacks;
use SmartcatSupport\descriptor\Option;

class Plugin extends AbstractPlugin implements HookSubscriber {

    public function start() {
        $this->add_api_subscriber( $this );
        $this->add_api_subscriber( include $this->dir . 'config/admin_settings.php' );

        $this->config_dir = $this->dir . '/config/';
        $this->template_dir = $this->dir . '/templates/';

        $this->woo_active = class_exists( 'WooCommerce' );
        $this->edd_active = class_exists( 'Easy_Digital_Downloads' );

        // Notify subscribers if there is a version upgrade
        $version = get_option( Option::PLUGIN_VERSION, 0 );

        // Perform migrations with the current version
        $upgrade = $this->perform_migrations( $version );

        if( !is_wp_error( $upgrade ) && $this->version > $version ) {
            do_action( $this->id . '_upgrade', $version, $this->version );
            update_option( Option::PLUGIN_VERSION, $this->version );
        }

        Mailer::init( $this );

        //include_once $this->dir . '/lib/tgm/tgmpa.php';
    }

    public function activate() {
        $this->configure_roles();
        \SmartcatSupport\proc\create_email_templates();
        $this->setup_template_page();
    }

    public function deactivate() {

        if( isset( $_POST['product_feedback'] ) ) {
            $message = include $this->dir . '/emails/product-feedback.php';
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );

            wp_mail( 'support@smartcat.ca', 'uCare Deactivation Feedback', $message, $headers );
        }

        // Trash the template page
        wp_trash_post( get_option( Option::TEMPLATE_PAGE_ID ) );

        $this->cleanup_roles();

        Mailer::cleanup();

        do_action( $this->id . '_cleanup' );

        if( get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on' ) {
            $options = new \ReflectionClass( Option::class );

            foreach( $options->getConstants() as $option ) {
                delete_option( $option );
            }

            update_option( Option::DEV_MODE, 'on' );
        }
    }

    public function add_settings_shortcut() {
        add_submenu_page( 'edit.php?post_type=support_ticket', '', __( 'Launch Help  Desk',  \SmartcatSupport\PLUGIN_ID ), 'manage_options', 'open_app', function () {} );
    }

    public function settings_shortcut_redirect() {
        if( isset( $_GET['page'] ) && $_GET['page'] == 'open_app' ) {
            wp_safe_redirect( get_the_permalink( get_option( Option::TEMPLATE_PAGE_ID ) ) );
        }
    }

    public function add_action_links( $links ) {

        if( !get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on' ) {
            $links['deactivate'] = '<span id="feedback-prompt">' . $links['deactivate'] . '</span>';
        }

        $menu_page = menu_page_url( 'support_options', false );

        return array_merge( array( 'settings' => '<a href="' . $menu_page . '">' . __( 'Settings', PLUGIN_ID ) . '</a>' ), $links );
    }

    public function admin_enqueue() {
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_script( 'wp_media_uploader',
            $this->url . 'assets/lib/wp_media_uploader.js', array( 'jquery' ), $this->version );

        wp_register_script( 'support-admin-js',
            $this->url . 'assets/admin/admin.js', array( 'jquery' ), $this->version );

        wp_localize_script( 'support-admin-js',
            'SupportSystem', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce( 'support_ajax' )
            )
        );
        wp_enqueue_script( 'support-admin-js' );

        wp_enqueue_style( 'support-admin-icons',
            $this->url . '/assets/icons/style.css', null, $this->version );

        wp_enqueue_style( 'support-admin-css',
            $this->url . '/assets/admin/admin.css', null, $this->version );
    }

    public function register_dependencies() {
        $plugins = array(
            array(
                'name'     => 'WP SMTP',
                'slug'     => 'wp-smtp',
                'required' => false
            )
        );

        $config = array(
            'id'           => 'smartcat_support_required_plugins',
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'parent_slug'  => 'plugins.php',
            'capability'   => 'manage_options',
            'has_notices'  => true,
            'dismissable'  => true,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => '',
            'strings'      => array(
                'notice_can_install_required' => _n_noop(
                    'Smartcat Support requires the following plugin: %1$s.',
                    'Smartcat Support requires the following plugins: %1$s.',
                    \SmartcatSupport\PLUGIN_ID
                ),
                'notice_can_install_recommended' => _n_noop(
                    'Smartcat Support recommends the following plugin: %1$s.',
                    'Smartcat Support recommends the following plugins: %1$s.',
                    \SmartcatSupport\PLUGIN_ID
                ),
            )
        );

        tgmpa( $plugins, $config );
    }

    public function login_failed() {
        if ( !empty( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'],  \SmartcatSupport\url() ) ) {
            wp_redirect( \SmartcatSupport\url() . '?login=failed' );
            exit;
        }
    }

    public function authenticate( $user, $username, $password ) {
        if( !empty( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'],  \SmartcatSupport\url() ) ) {
            if ( $username == "" || $password == "" ) {
                wp_redirect( \SmartcatSupport\url() . "?login=empty" );
                exit;
            }
        }
    }

    public function subscribed_hooks() {
        return array(
            'wp_login_failed' => array( 'login_failed' ),
            'authenticate' => array( 'authenticate', 1, 3 ),
            'admin_footer' => array( 'feedback_form' ),
            'plugin_action_links_' . plugin_basename( $this->file ) => array( 'add_action_links' ),
            'admin_menu' => array( 'add_settings_shortcut'),
            'admin_init' => array( 'settings_shortcut_redirect' ),
            'admin_enqueue_scripts' => array( 'admin_enqueue' ),
//            'tgmpa_register' => array( 'register_dependencies' ),
            'mailer_consumers' => array( 'mailer_checkin' ),
            'mailer_text_domain' => array( 'mailer_text_domain' ),
            'template_include' => array( 'swap_template' ),
            'pre_update_option_' . Option::RESTORE_TEMPLATE => array( 'restore_template' )
        );
    }

    public function mailer_checkin( $consumers ) {
        return $consumers[] = $this->id;
    }

    public function mailer_text_domain( $text_domain ) {
        return \SmartcatSupport\PLUGIN_ID;
    }

    public function components() {
        $components = array(
            TicketPostType::class,
            Ticket::class,
            Comment::class,
            Settings::class,
            Hacks::class,
            Media::class,
            ajax\Statistics::class
        );

        if( \SmartcatSupport\util\ecommerce_enabled( false ) ) {
            $components[] = ECommerce::class;
        }

        if( get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ) == 'on' ) {
            $components[] = Registration::class;
        }

        if( get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ) == 'on' ) {
            $components[] = Notifications::class;
        }

        return $components;
    }

    public function swap_template( $template ) {
        if( is_page( get_option( Option::TEMPLATE_PAGE_ID ) ) ) {
            $template = $this->template_dir . '/app.php';
        }

        return $template;
    }

    public function restore_template( $val ) {
        if( $val == 'on' ) {
            $this->setup_template_page();
        }

        return '';
    }

    public function feedback_form() {
        if( !get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on' ) {
            require_once $this->dir . '/templates/feedback.php';
        }
    }

    private function perform_migrations( $current ) {
        foreach ( glob($this->dir . 'migrations/migration-*.php' ) as $file ) {
            $migration = include_once( $file );
            $result = true;

            if( $migration->version() > $current ) {
                $result = $migration->migrate();

                if( is_wp_error( $result ) ) {
                    \SmartcatSupport\util\admin_notice( __( 'uCare failed to update', $this->id ), array( 'notice', 'notice-error' ) );
                    break;
                }
            }
        }

        return $result;
    }

    private function create_email_templates() {
        $welcome = get_option( Option::WELCOME_EMAIL_TEMPLATE );
        $closed = get_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE );
        $reply = get_option( Option::REPLY_EMAIL_TEMPLATE );
        $created = get_option( Option::CREATED_EMAIL_TEMPLATE );

        $default_style = file_get_contents( $this->dir . '/emails/default-style.css' );

        if( is_null( get_post( $created ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => __( 'You have created a new request for support', \SmartcatSupport\PLUGIN_ID ),
                    'post_content'  => file_get_contents( $this->dir . '/emails/ticket-created.html' )
                )
            );

            if( !empty( $id ) ) {
                update_post_meta( $id, 'styles', $default_style );
                update_option( Option::CREATED_EMAIL_TEMPLATE, $id );
            }
        } else {
            wp_untrash_post( $welcome );
        }

        if( is_null( get_post( $welcome ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => __( 'Welcome to Support', \SmartcatSupport\PLUGIN_ID ),
                    'post_content'  => file_get_contents( $this->dir . '/emails/welcome.html' )
                )
            );

            if( !empty( $id ) ) {
                update_post_meta( $id, 'styles', $default_style );
                update_option( Option::WELCOME_EMAIL_TEMPLATE, $id );
            }
        } else {
            wp_untrash_post( $welcome );
        }

        if( is_null( get_post( $closed ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => __( 'Your request for support has been closed', \SmartcatSupport\PLUGIN_ID ),
                    'post_content'  => file_get_contents( $this->dir . '/emails/ticket-closed.html' )
                )
            );

            if( !empty( $id ) ) {
                update_post_meta( $id, 'styles', $default_style );
                update_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE, $id );
            }
        } else {
            wp_untrash_post( $closed );
        }

        if( is_null( get_post( $reply ) ) ) {
            $id = wp_insert_post(
                array(
                    'post_type'     => 'email_template',
                    'post_status'   => 'publish',
                    'post_title'    => __( 'Reply to your request for support', \SmartcatSupport\PLUGIN_ID ),
                    'post_content'  => file_get_contents( $this->dir . '/emails/ticket-reply.html' )
                )
            );

            if( !empty( $id ) ) {
                update_post_meta( $id, 'styles', $default_style );
                update_option( Option::REPLY_EMAIL_TEMPLATE, $id );
            }
        } else {
            wp_untrash_post( $reply );
        }
    }

    private function setup_template_page() {
        $post_id = null;
        $post = get_post( get_option( Option::TEMPLATE_PAGE_ID ) ) ;

        if( empty( $post ) ) {
            $post_id = wp_insert_post(
                array(
                    'post_type' =>  'page',
                    'post_status' => 'publish',
                    'post_title' => __( 'Support', \SmartcatSupport\PLUGIN_ID )
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

    private function configure_roles() {
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

    private function cleanup_roles() {
        foreach( \SmartcatSupport\util\roles() as $role => $name ) {
            remove_role( $role );
        }

        \SmartcatSupport\util\remove_caps( 'customer' );
        \SmartcatSupport\util\remove_caps( 'subscriber' );
        \SmartcatSupport\util\remove_caps( 'administrator' );
    }
}
