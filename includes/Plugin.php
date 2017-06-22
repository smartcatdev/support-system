<?php

namespace ucare;

use smartcat\admin\MenuPage;
use smartcat\admin\TabbedMenuPage;
use smartcat\core\AbstractPlugin;
use ucare\admin\LogsTab;
use ucare\admin\ReportsOverviewTab;
use ucare\ajax\Media;
use ucare\ajax\Statistics;
use ucare\ajax\Ticket;
use ucare\ajax\Comment;
use ucare\ajax\Settings;
use ucare\ajax\Registration;
use ucare\component\ECommerce;
use ucare\component\Hacks;

class Plugin extends AbstractPlugin {

    private $menu_pages = array();
    private $activations = array();

    public function start() {
        $this->config_dir = $this->dir . '/config/';
        $this->template_dir = $this->dir . '/templates/';

        $this->woo_active = class_exists( 'WooCommerce' );
        $this->edd_active = class_exists( 'Easy_Digital_Downloads' );

        proc\configure_roles();

    }

    public function activate() {
        proc\configure_roles();
        proc\create_email_templates();
        proc\setup_template_page();
        proc\schedule_cron_jobs();
    }

    public function deactivate() {
        
        // Delete the first run option on de-activate
        // This triggers the First Run welcome screen to load on reload
        delete_option( Options::FIRST_RUN );
        
        if( isset( $_POST['product_feedback'] ) ) {
            $message = include $this->dir . '/emails/product-feedback.php';
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );

            wp_mail( 'support@smartcat.ca', 'uCare Deactivation Feedback', $message, $headers );
        }

        // Trash the template page
        wp_trash_post( get_option( Options::TEMPLATE_PAGE_ID ) );

        proc\cleanup_roles();
        proc\clear_scheduled_jobs();

        do_action( $this->id . '_cleanup' );

        if( get_option( Options::DEV_MODE ) === 'on' && get_option( Options::NUKE ) === 'on' ) {
            $options = new \ReflectionClass( Options::class );

            foreach( $options->getConstants() as $option ) {
                delete_option( $option );
            }

            update_option( Options::DEV_MODE, 'on' );
        }

        unregister_post_type( 'support_ticket' );
        unregister_taxonomy( 'ticket_category' );

    }

    public function login_failed() {
        if ( !empty( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], support_page_url() ) ) {
            wp_redirect( \ucare\support_page_url() . '?login=failed' );
            exit;
        }
    }

    public function authenticate( $user, $username, $password ) {
        if( !empty( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], support_page_url() ) ) {
            if ( $username == "" || $password == "" ) {
                wp_redirect( \ucare\support_page_url() . "?login=empty" );
                exit;
            }
        }
    }

    public function register_menu() {

        $this->menu_pages = array(
            'root' => new MenuPage(
                array(
                    'type'          => 'menu',
                    'menu_slug'     => 'ucare_support',
                    'menu_title'    => __( 'uCare Support', 'ucare' ),
                    'capability'    => 'manage_support',
                    'position'      => 71,
                    'icon'          => \ucare\plugin_url() . '/assets/images/admin-icon.png',
                    'render'        => false
                )
            ),
            'reports' => new TabbedMenuPage(
                array(
                    'type'          => 'submenu',
                    'parent_menu'   => 'ucare_support',
                    'menu_slug'     => 'ucare_support',
                    'menu_title'    => __( 'Reports', 'ucare' ),
                    'capability'    => 'manage_support',
                    'tabs' => get_option( Options::LOGGING_ENABLED ) == 'on'
                                ? array( new ReportsOverviewTab(), new LogsTab() )
                                : array( new ReportsOverviewTab() )
                )
            ),
            'tickets' => new MenuPage(
                array(
                    'type'        => 'submenu',
                    'parent_menu' => 'ucare_support',
                    'menu_title'  => __( 'Tickets', 'ucare' ),
                    'menu_slug'   => 'edit.php?post_type=support_ticket',
                    'capability'  => 'edit_support_tickets',
                    'render'      => false
                )
            ),
            'create_ticket' => new MenuPage(
                array(
                    'type'        => 'submenu',
                    'parent_menu' => 'ucare_support',
                    'menu_title'  => __( 'Create Ticket', 'ucare' ),
                    'menu_slug'   => 'post-new.php?post_type=support_ticket',
                    'capability'  => 'edit_support_tickets',
                    'render'      => false
                )
            ),
            'categories' => new MenuPage(
                array(
                    'type'        => 'submenu',
                    'parent_menu' => 'ucare_support',
                    'menu_title'  => __( 'Ticket Categories', 'ucare' ),
                    'menu_slug'   => 'edit-tags.php?post_type=support_ticket&taxonomy=ticket_category',
                    'capability'  => 'edit_support_tickets',
                    'render'      => false
                )
            ),
            'launcher' => new MenuPage(
                array(
                    'type'          => 'submenu',
                    'parent_menu'   => 'ucare_support',
                    'menu_slug'     => 'uc-launch',
                    'menu_title'    => __( 'Help Desk', 'ucare' ),
                    'capability'    => 'manage_support',
                    'onload'        => function () { wp_safe_redirect( support_page_url() ); }
                )
            ),
            'settings'   => include_once $this->dir . '/config/admin_settings.php',
            'extensions' => new MenuPage(
                array(
                    'type'          => 'submenu',
                    'parent_menu'   => 'ucare_support',
                    'menu_slug'     => 'uc-add-ons',
                    'menu_title'    => __( 'Add-ons', 'ucare' ),
                    'render'        => $this->template_dir . '/admin-extensions.php'
                )
            )
        );

        if ( !empty( $this->activations ) ) {

            $this->menu_pages['licenses'] = new MenuPage(
                array(
                    'type'        => 'submenu',
                    'parent_menu' => 'ucare_support',
                    'menu_slug'   => 'uc-licenses',
                    'menu_title'  => __('Licenses', 'ucare'),
                    'render'      => $this->template_dir . '/admin-activations.php'
                )
            );

        }

        do_action( 'support_menu_register', $this->menu_pages );

    }

    public function get_activations() {
        return $this->activations;
    }

    public function add_activation( $id, $args ) {

        if( !in_array( $id, $this->activations ) ) {

            $activation = array(
                'store_url'      => $args['store_url'],
                'support_file'   => $args['file'],
                'status_option'  => $args['status_option'],
                'license_option' => $args['license_option'],
                'expire_option'  => $args['expire_option'],
                'version'        => $args['version'],
                'item_name'      => $args['item_name'],
                'author'         => $args['author'],
                'beta'           => !empty( $args['beta'] )
            );

            $this->activations [ $id ] = $activation;

            return true;

        }

        return false;

    }

    public function remove_activation( $id ) {

        if( !in_array( $id, $this->activations ) ) {

            unset( $this->activations['id'] );

            return true;

        }

        return false;

    }

    public function subscribed_hooks() {
        return parent::subscribed_hooks( array(
            'wp_loaded'         => 'register_menu',
            'wp_login_failed'   => array( 'login_failed' ),
            'authenticate'      => array( 'authenticate', 1, 3 ),
            'admin_footer'      => array( 'feedback_form' ),
            'template_include' => array( 'swap_template' ),
            'pre_update_option_' . Options::RESTORE_TEMPLATE => array( 'restore_template' )
        ) );
    }

    public function components() {
        $components = array(
            Ticket::class,
            Comment::class,
            Settings::class,
            Hacks::class,
            Media::class,
            Statistics::class
        );

        if( \ucare\util\ecommerce_enabled( false ) ) {
            $components[] = ECommerce::class;
        }

        if( get_option( Options::ALLOW_SIGNUPS, \ucare\Defaults::ALLOW_SIGNUPS ) == 'on' ) {
            $components[] = Registration::class;
        }

        return $components;
    }

    public function swap_template( $template ) {
        if( is_page( get_option( Options::TEMPLATE_PAGE_ID ) ) ) {
            $template = $this->template_dir . '/app.php';
        }

        return $template;
    }

    public function restore_template( $val ) {
        if( $val == 'on' ) {
            \ucare\proc\setup_template_page();
        }

        return '';
    }

    public function feedback_form() {

        if( !get_option( Options::DEV_MODE, \ucare\Defaults::DEV_MODE ) == 'on' ) {
            require_once $this->dir . '/templates/feedback.php';
        }
    }
}
