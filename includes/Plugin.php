<?php

namespace ucare;

use smartcat\core\AbstractPlugin;
use smartcat\mail\Mailer;
use ucare\ajax\Media;
use ucare\ajax\Ticket;
use ucare\ajax\Comment;
use ucare\ajax\Settings;
use ucare\ajax\Registration;
use ucare\component\ECommerce;
use ucare\component\Notifications;
use ucare\admin\TicketPostType;
use ucare\component\Hacks;
use ucare\descriptor\Option;

class Plugin extends AbstractPlugin {

    public function start() {
        $this->add_api_subscriber( include $this->dir . 'config/admin_settings.php' );

        $this->config_dir = $this->dir . '/config/';
        $this->template_dir = $this->dir . '/templates/';

        $this->woo_active = class_exists( 'WooCommerce' );
        $this->edd_active = class_exists( 'Easy_Digital_Downloads' );

        Mailer::init( $this );

        proc\configure_roles();
    }

    public function activate() {
        proc\configure_roles();
        proc\create_email_templates();
        proc\setup_template_page();
    }

    public function deactivate() {

        if( isset( $_POST['product_feedback'] ) ) {
            $message = include $this->dir . '/emails/product-feedback.php';
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );

            wp_mail( 'support@smartcat.ca', 'uCare Deactivation Feedback', $message, $headers );
        }

        // Trash the template page
        wp_trash_post( get_option( Option::TEMPLATE_PAGE_ID ) );

        proc\cleanup_roles();

        Mailer::cleanup();

        do_action( $this->id . '_cleanup' );

        if( get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on' && get_option( Option::NUKE, Option\Defaults::NUKE ) == 'on' ) {
            $options = new \ReflectionClass( Option::class );

            foreach( $options->getConstants() as $option ) {
                delete_option( $option );
            }

            update_option( Option::DEV_MODE, 'on' );
        }
    }

    public function add_action_links( $links ) {

        if( !get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on' ) {
            $links['deactivate'] = '<span id="feedback-prompt">' . $links['deactivate'] . '</span>';
        }

        $menu_page = menu_page_url( 'support_options', false );

        return array_merge( array( 'settings' => '<a href="' . $menu_page . '">' . __( 'Settings', PLUGIN_ID ) . '</a>' ), $links );
    }

    public function admin_enqueue( $hook ) {
            wp_enqueue_script( 'jquery-ui-datepicker' );

            wp_enqueue_media();

            wp_enqueue_style( 'wp-color-picker');
            wp_enqueue_script( 'wp-color-picker');

            wp_enqueue_script( 'wp_media_uploader',
                $this->url . 'assets/lib/wp_media_uploader.js', array( 'jquery' ), $this->version );

            wp_register_script('support-admin-js',
                $this->url . 'assets/admin/admin.js', array( 'jquery' ), $this->version );

            wp_localize_script( 'support-admin-js',
                'SupportSystem', array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'ajax_nonce' => wp_create_nonce( 'support_ajax' )
                )
            );
            wp_enqueue_script( 'support-admin-js');

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
                    PLUGIN_ID
                ),
                'notice_can_install_recommended' => _n_noop(
                    'Smartcat Support recommends the following plugin: %1$s.',
                    'Smartcat Support recommends the following plugins: %1$s.',
                    PLUGIN_ID
                ),
            )
        );

        tgmpa( $plugins, $config );
    }

    public function login_failed() {
        if ( !empty( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'],  \ucare\url() ) ) {
            wp_redirect( url() . '?login=failed' );
            exit;
        }
    }

    public function authenticate( $user, $username, $password ) {
        if( !empty( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'],  \ucare\url() ) ) {
            if ( $username == "" || $password == "" ) {
                wp_redirect( url() . "?login=empty" );
                exit;
            }
        }
    }

    public function register_menu_items() {
        add_menu_page(
            __( 'uCare Support', PLUGIN_ID ),
            __( 'uCare Support', PLUGIN_ID ),
            'manage_support',
            'ucare_support',
            '',
            $this->url . 'assets/images/admin-icon.png',
            71
        );

        do_action( 'support_menu_register' );

        add_submenu_page(
            'ucare_support',
            '', __( 'Launch Help Desk', PLUGIN_ID ),
            'manage_support',
            'open_app',
            function () {
                wp_safe_redirect( get_the_permalink( get_option( Option::TEMPLATE_PAGE_ID ) ) );
            }
        );
    }

    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'admin_menu'        => array( 'register_menu_items', 1, 0 ),
            'wp_login_failed'   => array( 'login_failed' ),
            'authenticate'      => array( 'authenticate', 1, 3 ),
            'admin_footer'      => array( 'feedback_form' ),
            'plugin_action_links_' . plugin_basename( $this->file ) => array( 'add_action_links' ),
            'admin_enqueue_scripts' => array( 'admin_enqueue' ),
//            'tgmpa_register' => array( 'register_dependencies' ),
            'mailer_consumers' => array( 'mailer_checkin' ),
            'mailer_text_domain' => array( 'mailer_text_domain' ),
            'template_include' => array( 'swap_template' ),
            'pre_update_option_' . Option::RESTORE_TEMPLATE => array( 'restore_template' )
        ) );
    }

    public function mailer_checkin( $consumers ) {
        return $consumers[] = $this->id;
    }

    public function mailer_text_domain( $text_domain ) {
        return PLUGIN_ID;
    }

    public function components() {
        $components = array(
            TicketPostType::class,
            Ticket::class,
            Comment::class,
            Settings::class,
            Hacks::class,
            Media::class,
            ajax\Statistics::class,
            admin\Reports::class
        );

        if( util\ecommerce_enabled( false ) ) {
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
            proc\setup_template_page();
        }

        return '';
    }

    public function feedback_form( $hook ) {
        if( !get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ) == 'on' ) {
            require_once $this->dir . '/templates/feedback.php';
        }
    }
}
