<?php

namespace SmartcatSupport;

use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;
use smartcat\mail\MailerComponent;
use SmartcatSupport\admin\FormMetaBox;
use SmartcatSupport\admin\PostTableActions;
use SmartcatSupport\ajax\CommentActions;
use SmartcatSupport\ajax\TicketHandler;
use SmartcatSupport\ajax\TicketTable;
use SmartcatSupport\descriptor\Option;
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

//    $installer = Installer::init();
   // MailerComponent::register( 'Smartcat Support', PLUGIN_NAME );

    // Configure table Handler
    $table_handler = new TicketTable();

    // Configure ticket Handler
//    $ticket_handler = new TicketHandler();



//   new FormMetaBox(
//        array(
//           'id'         => 'ticket_support_meta',
//            'title'     => __( 'Ticket Information', PLUGIN_NAME ),
//            'post_type' => 'support_ticket',
//            'context'   => 'advanced',
//            'priority'  => 'high',
//            'config'    =>  SUPPORT_PATH . '/config/support_metabox_form.php'
//        )
//    );
//
//    new FormMetaBox(
//        array(
//            'id'         => 'ticket_product_meta',
//            'title'     => __( 'Product Information', PLUGIN_NAME ),
//            'post_type' => 'support_ticket',
//            'context'   => 'side',
//            'priority'  => 'high',
//            'config'    =>  SUPPORT_PATH . '/config/product_metabox_form.php'
//        )
//    );
//
//    new FormMetaBox(
//        array(
//            'id'         => 'ticket_customer_meta',
//            'title'     => __( 'Customer Information', PLUGIN_NAME ),
//            'post_type' => 'support_ticket',
//            'context'   => 'side',
//            'priority'  => 'high',
//            'config'    =>  SUPPORT_PATH . '/config/customer_metabox_form.php'
//        )
//    );

//    PostTableActions::init();
//    CommentActions::init();



   // include_once SUPPORT_PATH . '/config/admin_settings.php';


}

/**
 * Decode HTML chars between <code></code> tags.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @since 1.0.0
 * @param $text
 * @return String
 */
//function convert_html_chars( $text ) {
//    $matches = array();
//
//    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );
//
//    foreach( $matches[1] as $match ) {
//        $text = str_replace( $match, htmlspecialchars( $match ), $text );
//    }
//
//    return $text;
//}

/**
 * Get a list of all users with the Support Agent Role.
 *
 * @author Eric Green <eric@smartcat.ca>
 * @return array The list of agents
 * @since 1.0.0
 */
function get_agents( ) {
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

    include ( SUPPORT_PATH . '/template-parts/' . $template . '.php' );

    return ob_get_clean();
}

//function register_form() {
//    $form = new Form( 'register_form' );
//
//    $form->add_field( new TextBoxField(
//        array(
//            'id'            => 'first_name',
//            'label'         => __( 'First Name', TEXT_DOMAIN ),
//            'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
//            'constraints'   => array(
//                new RequiredConstraint()
//            )
//        )
//
//    ) )->add_field( new TextBoxField(
//        array(
//            'id'            => 'last_name',
//            'label'         => __( 'Last Name', TEXT_DOMAIN ),
//            'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
//            'constraints'   =>  array(
//                new RequiredConstraint()
//            )
//        )
//
//    ) )->add_field( new TextBoxField(
//        array(
//            'id'            => 'email',
//            'type'              => 'email',
//            'label'             => __( 'Email Address', TEXT_DOMAIN ),
//            'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
//            'sanitize_callback' => 'sanitize_email',
//            'constraints'       => array(
//                new RequiredConstraint()
//            )
//        )
//    ) );
//
//    return $form;
//}

//function register_user() {
//    $form = register_form();
//
//    if( $form->is_valid() ) {
//        $data = $form->data;
//        $password = wp_generate_password();
//
//        $user_id = wp_insert_user(
//            array(
//                'user_login'    => sanitize_title( $data['first_name'] . ' ' . $data['last_name'] ),
//                'user_email'    => $data['email'],
//                'first_name'    => $data['first_name'],
//                'last_name'     => $data['last_name'],
//                'role'          => 'support_user',
//                'user_pass'     => $password
//            )
//        );
//
//        add_filter( 'replace_email_template_vars', function( $vars ) use ( $password ) {
//            $vars['password'] = $password;
//
//            return $vars;
//        } );
//
//        do_action( 'smartcat_send_mail', get_option( Option::WELCOME_EMAIL_TEMPLATE ), $data['email'] );
//
//        wp_set_auth_cookie( $user_id );
//        wp_send_json_success();
//    } else {
//        wp_send_json_error( $form->errors );
//    }
//}
