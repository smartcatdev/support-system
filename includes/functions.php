<?php

namespace SmartcatSupport;

use smartcat\mail\EmailTemplateService;
use SmartcatSupport\admin\CustomerMetaBox;
use SmartcatSupport\admin\ProductMetaBox;
use SmartcatSupport\admin\SupportMetaBox;
use SmartcatSupport\admin\TicketAdminTable;
use SmartcatSupport\ajax\Comment;
use SmartcatSupport\ajax\Ticket;
use SmartcatSupport\ajax\TicketTable;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\constraint\Required;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\util\Installer;


/**
 * Composition Root for the plugin.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 */
function bootstrap( $fs_context ) {
    define( 'SUPPORT_PATH', dirname( $fs_context ) );
    define( 'SUPPORT_URL', plugin_dir_url( $fs_context ) );

    $installer = Installer::init();
    EmailTemplateService::register( 'Smartcat Support', TEXT_DOMAIN );

    // Configure table Handler
    $table_handler = new TicketTable();

    // Configure ticket Handler
    $ticket_handler = new Ticket( new FormBuilder( 'ticket_form' ) );

    // Configure comment handler
    $comment_handler = new Comment( new FormBuilder( 'comment_form' ) );

    // Configure the metabox
    $support_metabox = new SupportMetaBox( new FormBuilder( 'metabox_support_form' ) );

    $product_metabox = new ProductMetaBox( new FormBuilder( 'metabox_product_form' ) );

    $customer_metabox = new CustomerMetaBox( new FormBuilder( 'metabox_customer_form' ) );

    $ticket_admin = new TicketAdminTable();


    include_once 'hooks.php';
    include_once 'admin.php';


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

    if( defined( 'SUPPORT_WOO_ACTIVE' ) && get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ) ) {
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

    if( defined( 'SUPPORT_EDD_ACTIVE' ) && get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ) ) {
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
    extract( $data );
    ob_start();

    include ( SUPPORT_PATH . '/templates/' . $template . '.php' );

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
        $password = wp_generate_password();

        $user_id = wp_insert_user(
            array(
                'user_login'    => sanitize_title( $data['first_name'] . ' ' . $data['last_name'] ),
                'user_email'    => $data['email'],
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'role'          => 'support_user',
                'user_pass'     => $password
            )
        );

        add_filter( 'replace_email_template_vars', function( $vars ) use ( $password ) {
            $vars['password'] = $password;

            return $vars;
        } );

        do_action( 'smartcat_send_mail', get_option( Option::WELCOME_EMAIL_TEMPLATE ), $data['email'] );

        wp_set_auth_cookie( $user_id );
        wp_send_json_success();
    } else {
        wp_send_json_error( $form->get_errors() );
    }
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

function boolean_meta_dropdown( $name, $selected = '', $echo = true ) {
    $select = new SelectBox( $name,
        array(
            'value' => $selected,
            'options' => array( '' => __( 'All', TEXT_DOMAIN ), 'flagged' => __( 'Flagged', TEXT_DOMAIN ) )
        )
    );

    if( $echo ) {
        $select->render();
        $select = null;
    }

    return $select;
}
