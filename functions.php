<?php

namespace SmartcatSupport;

use SmartcatSupport\admin\SupportMetaBox;
use SmartcatSupport\ajax\Comment;
use SmartcatSupport\ajax\Ticket;
use SmartcatSupport\ajax\TicketTable;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\constraint\Required;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\util\Installer;
use SmartcatSupport\util\TicketCPT;

/**
 * Composition Root for the plugin.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 */
function init( $fs_context ) {

    // Configure the application
//    $plugin_dir = plugin_dir_path( $fs_context );
//    $plugin_url = plugin_dir_url( $fs_context );

    // Configure table Handler
    $table_handler = new TicketTable();

    // Configure ticket Handler
    $ticket_handler = new Ticket( new FormBuilder( 'ticket_form' ) );

    // Configure comment handler
    $comment_handler = new Comment( new FormBuilder( 'comment_form' ) );

    // Configure the metabox
    $support_metabox = new SupportMetaBox( new FormBuilder( 'metabox_form' ) );

    $ticket_cpt = new TicketCPT();

    // Configure installer
    $installer = new Installer();

    add_action( 'plugins_loaded', function() use ( $fs_context ) {
        define( 'SUPPORT_WOO_ACTIVE', class_exists( 'WooCommerce' ) );
        define( 'SUPPORT_EDD_ACTIVE', class_exists( 'Easy_Digital_Downloads' ) );
        define( 'SUPPORT_PATH', dirname( $fs_context ) );
        define( 'SUPPORT_URL', plugin_dir_url( $fs_context ) );
    } );

    add_action( 'template_include', function ( $template ) {
        if( is_page( get_option( Option::TEMPLATE_PAGE_ID ) ) ) {
            $template = SUPPORT_PATH . '/templates/template.php';
        }

        return $template;
    } );

    if( get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ) ) {
        add_action( 'wp_ajax_nopriv_support_register_user', '\SmartcatSupport\register_user' );
    }

    // Temporarily add/remove roles until we get a settings page
    add_action( 'admin_init', function() {
        if ( SUPPORT_EDD_ACTIVE ) {
            if ( get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ) ) {
                append_user_caps( 'subscriber' );
            } else {
                remove_appended_caps( 'subscriber' );
            }
        }

        if ( SUPPORT_WOO_ACTIVE ) {
            if ( get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ) ) {
                append_user_caps( 'customer' );
            } else {
                remove_appended_caps( 'customer' );
            }
        }
    } );

    add_action( 'admin_enqueue_scripts', function ( $hook ) {
        if( $hook = 'edit.php?post_type=support_ticket') {
            wp_register_script( 'support-admin-js',
                SUPPORT_URL . 'assets/admin/admin.js', array( 'jquery' ), PLUGIN_VERSION );

            wp_localize_script( 'support-admin-js', 'SupportSystem', array( 'ajaxURL' => admin_url( 'admin-ajax.php' ) ) );
            wp_enqueue_script( 'support-admin-js' );

            wp_enqueue_style( 'support-admin-icons', SUPPORT_URL . '/assets/icons.css', null, PLUGIN_VERSION );
            wp_enqueue_style( 'support-admin-css', SUPPORT_URL . '/assets/admin/admin.css', null, PLUGIN_VERSION );
        }
    } );

    //<editor-fold desc="Enqueue Assets">
//    add_action( 'wp_enqueue_scripts', function() use ( $plugin_url ) {
//        wp_enqueue_script( 'datatables',
//            $plugin_url . 'assets/lib/datatables/datatables.min.js', array( 'jquery' ), PLUGIN_VERSION );
//
//        wp_enqueue_style( 'datatables',
//            $plugin_url . 'assets/lib/datatables/datatables.min.css', array(), PLUGIN_VERSION );
//
//        wp_enqueue_style( 'jquery-modal',
//            $plugin_url . 'assets/lib/modal/jquery.modal.min.css', array(), PLUGIN_VERSION );
//
//        wp_enqueue_script( 'jquery-modal',
//            $plugin_url . 'assets/lib/modal/jquery.modal.min.js', array( 'jquery' ), PLUGIN_VERSION );
//
//        wp_enqueue_script( 'tabular',
//            $plugin_url . 'assets/lib/tabular.js', array('jquery' ), PLUGIN_VERSION );
//
//        wp_enqueue_script( 'tinymce_js',
//            includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', array( 'jquery' ), false, true );
//
//        wp_register_script( 'support_system_lib',
//            $plugin_url . 'assets/js/app.js', array( 'jquery', 'jquery-ui-tabs' ), PLUGIN_VERSION );
//
//        wp_localize_script( 'support_system_lib', 'SupportSystem', array( 'ajaxURL' => admin_url( 'admin-ajax.php' ) ) );
//        wp_enqueue_script( 'support_system_lib' );
//
//        wp_enqueue_script( 'support_system_script',
//            $plugin_url . 'assets/js/script.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-core', 'support_system_lib' ), PLUGIN_VERSION );
//
//        wp_enqueue_style( 'support_system_style',
//            $plugin_url . 'assets/css/style.css', array(), PLUGIN_VERSION );
//
//        wp_enqueue_style( 'support_system_appearance',
//            $plugin_url . 'assets/css/common.css', array(), PLUGIN_VERSION );
//
//        wp_enqueue_style( 'support_system_datatables',
//            $plugin_url . 'assets/css/datatables.css', array(), PLUGIN_VERSION );
//
//        wp_enqueue_style( 'support_system_icons',
//            $plugin_url . 'assets/icons.css', array(), PLUGIN_VERSION );
//    } );
    //</editor-fold>

    register_activation_hook( $fs_context, array( $installer, 'activate' ) );
    register_deactivation_hook( $fs_context, array( $installer, 'deactivate' ) );
}

/**
 * Decode HTML chars between <code></code> tags.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 * @param $text
 * @return String
 */
function convert_html_chars( $text ) {
    $matches = array();

    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );

    foreach( $matches[1] as $match ) {
        $text = str_replace( $match, htmlspecialchars( $match ), $text );
    }

    return $text;
}

/**
 * Get a list of all users with the Support Agent Role.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @return array The list of agents
 * @since 1.0.0
 */
function get_agents() {
    $agents = array();
    $users = get_users( array( 'role' => array( 'support_agent' ) ) );

    if( $users != null ) {
        foreach( $users as $user ) {
            $agents[ $user->ID ] = $user->display_name;
        }
    }

    return $agents;
}

/**
 * Get a list of Products and/or Downloads if EDD or WooCommerce is active.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @return array Empty if neither is active, else an array of post titles and IDs.
 * @since 1.0.0
 */
function get_products() {
    $results = array();

    if( SUPPORT_WOO_ACTIVE && get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ) ) {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
        );

        $query = new \WP_Query( $args );

        while( $query->have_posts() ) {
            $results[ $query->post->ID ] = $query->post->post_title;

            $query->next_post();
        }
    }

    if( SUPPORT_EDD_ACTIVE && get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ) ) {
        $args = array(
            'post_type' => 'download',
            'post_status' => 'publish',
        );

        $query = new \WP_Query( $args );

        while( $query->have_posts() ) {
            $results[ $query->post->ID ] = $query->post->post_title;

            $query->next_post();
        }
    }

    return $results;
}

/**
 * Render the template and capture its output.
 *
 * @param string $template The template to render.
 * @param array $data (Default empty) Any data required to be output in the template.
 * @return string The rendered HTML.
 * @since 1.0.0
 * @author Eric Green <eric@smartcat.ca>
 */
function render_template( $template, array $data = array() ) {
    if( is_array( $data ) ) {
        extract( $data );
    }

    ob_start();

    include ( plugin_dir_path( __FILE__ ) . 'templates/' . $template . '.php' );

    return ob_get_clean();
}

function register_form() {
    $builder = new FormBuilder( 'register_form' );

    $builder->add( TextBox::class, 'first_name', array(
        'label'             => __( 'First Name', TEXT_DOMAIN ),
        'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'       => array(
            $builder->create_constraint( Required::class )
        )

    ) )->add( TextBox::class, 'last_name', array(
        'label'             => __( 'Last Name', TEXT_DOMAIN ),
        'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'       =>  array(
            $builder->create_constraint( Required::class )
        )

    ) )->add( TextBox::class, 'email', array(
        'type'              => 'email',
        'label'             => __( 'Email Address', TEXT_DOMAIN ),
        'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
        'sanitize_callback' => 'sanitize_email',
        'constraints'       => array(
            $builder->create_constraint( Required::class )
        )

    ) );

    return $builder->get_form();
}

function register_user() {
    $form = register_form();

    if( $form->is_valid() ) {
        $data = $form->get_data();

        $user_id = register_new_user(
            sanitize_title( $data['first_name'] . ' ' . $data['last_name'] ), $data['email']
        );

        if( !empty( $user_id ) ) {
            get_user_by( 'ID', $user_id )->set_role( 'support_user' );
            wp_set_auth_cookie( $user_id );
            wp_send_json_success();
        }

    } else {
        wp_send_json_error( $form->get_errors() );
    }
}

function append_user_caps( $role ) {
    $role = get_role( $role );

    $role->add_cap( 'view_support_tickets' );
    $role->add_cap( 'create_support_tickets' );
    $role->add_cap( 'unfiltered_html' );
}

function remove_appended_caps( $role ) {
    $role = get_role( $role );

    $role->remove_cap( 'view_support_tickets' );
    $role->remove_cap( 'create_support_tickets' );
    $role->remove_cap( 'unfiltered_html' );
}

function agents_dropdown( $name, $selected = '', $echo = true ) {
    $select = new SelectBox( $name,
        array(
            'value' => $selected,
            'options' => array( '' => __( 'All Agents', TEXT_DOMAIN ) ) + get_agents()
        )
    );

    if( $echo ) {
        $select->render();
        $select = null;
    }

    return $select;
}

function products_dropdown( $name, $selected = '', $echo = true ) {
    $select = new SelectBox( $name,
        array(
            'value' => $selected,
            'options' => array( '' => __( 'All Products', TEXT_DOMAIN ) ) + get_products()
        )
    );

    if( $echo ) {
        $select->render();
        $select = null;
    }

    return $select;
}
