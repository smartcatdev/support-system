<?php

namespace smartcat\mail;

// Check to make sure the mailer hasn't already been loaded
if( !function_exists( '\smartcat\mail\init' ) ) {

    function __( $text ) {
        return \__( $text, apply_filters( 'mailer_text_domain', '' ) );
    }

    function _x( $text, $context ) {
        return \_x( $text, $context, apply_filters( 'mailer_text_domain', '' ) );
    }

    function send_template( $template_id, $recipient, $replace = array(), $args = array() ) {
        $template = get_post( $template_id );
        $sent = false;
        
        if( !empty( $template ) ) {

            add_filter( 'mailer_template_vars', function ( $vars ) use ( $replace ) {
                return array_merge( $vars, $replace );
            } );

            $content = parse_template( $template->post_content, $template, $recipient );
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );

            $sent = wp_mail(
                $recipient,
                isset( $replace['ticket_number'] ) ? apply_filters( 'ucare_email_subject', $template->post_title, $replace['ticket_number'] ) : $template->post_title,
                wrap_template( $template, $content, $args ),
                apply_filters( 'mailer_email_headers', $headers, $template_id, $recipient )
            );
        }

        return $sent;
    }

    function parse_template( $content, $template, $recipient ) {
        $user = get_user_by( 'email', $recipient );

        $defaults = array(
            'username'       => $user->user_login,
            'first_name'     => $user->first_name,
            'last_name'      => $user->last_name,
            'full_name'      => $user->first_name . ' ' . $user->last_name,
            'template_title' => $template->post_title,
            'email'          => !empty( $user ) ? $user->user_email : $recipient,
            'home_url'       => home_url()
        );

        $vars = apply_filters( 'mailer_template_vars', $defaults, $recipient, $template );

        foreach( $vars as $shortcode => $value ) {
            $content = stripcslashes( str_replace( '{%' . $shortcode . '%}', $value, $content ) );
        }

        return $content;
    }

    function wrap_template( $template, $content, $args ) {
        ob_start(); ?>
        <?php do_action( 'ucare_above_email' ); ?>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <style type="text/css"><?php echo wp_strip_all_tags( get_post_meta( $template->ID, 'styles', true ) ); ?></style>
            <style>
                .footer {
                    margin-top: 20px;
                    text-align: center;
                }
            </style>
            <?php echo do_action( 'email_template_header', $template, $args ); ?>
        </head>
        <body>
            <?php echo $content; ?>
        <div class="footer">
            <?php echo do_action( 'email_template_footer', $template, $args ); ?>
        </div>
        </body>
        </html>

        <?php return ob_get_clean();
    }

    function list_templates() {
        global $wpdb;

        $results = array();
        $templates = $wpdb->get_results(
            "SELECT ID, post_title 
             FROM {$wpdb->prefix}posts 
             WHERE post_type='email_template' 
                AND post_status='publish'"
        );

        foreach( $templates as $template ) {
            $results[ $template->ID ] = $template ->post_title;
        }

        return $results;
    }

    function add_caps() {
        $administrator = get_role( 'administrator' );

        $administrator->add_cap( 'read_email_template' );
        $administrator->add_cap( 'read_email_templates' );
        $administrator->add_cap( 'edit_email_template' );
        $administrator->add_cap( 'edit_email_templates' );
        $administrator->add_cap( 'delete_email_templates' );
        $administrator->add_cap( 'edit_others_email_templates' );
        $administrator->add_cap( 'edit_published_email_templates' );
        $administrator->add_cap( 'publish_email_templates' );
        $administrator->add_cap( 'delete_others_email_templates' );
        $administrator->add_cap( 'delete_private_email_templates' );
        $administrator->add_cap( 'delete_published_email_templates' );
    }

    function remove_caps() {
        $administrator = get_role( 'administrator' );

        $administrator->remove_cap( 'read_email_template' );
        $administrator->remove_cap( 'read_email_templates' );
        $administrator->remove_cap( 'edit_email_template' );
        $administrator->remove_cap( 'edit_email_templates' );
        $administrator->remove_cap( 'delete_email_templates' );
        $administrator->remove_cap( 'edit_others_email_templates' );
        $administrator->remove_cap( 'edit_published_email_templates' );
        $administrator->remove_cap( 'publish_email_templates' );
        $administrator->remove_cap( 'delete_others_email_templates' );
        $administrator->remove_cap( 'delete_private_email_templates' );
        $administrator->remove_cap( 'delete_published_email_templates' );
    }

    function cleanup() {
        remove_caps();
        unregister_post_type( 'email_template' );
    }

    function init() {
        $metabox = new TemplateStyleMetaBox();

        add_caps();
    }

    add_action( 'init', 'smartcat\mail\init' );


    function register_template_post_type() {
        $labels = array(
            'name'                  => _x( 'Email Templates', 'Post Type General Name' ),
            'singular_name'         => _x( 'Email Template', 'Post Type Singular Name' ),
            'menu_name'             => __( 'Email Templates' ),
            'name_admin_bar'        => __( 'Email Templates' ),
            'archives'              => __( 'Template Archives' ),
            'parent_item_colon'     => __( 'Parent Item:' ),
            'all_items'             => __( 'All Templates' ),
            'add_new_item'          => __( 'New Template' ),
            'add_new'               => __( 'New Template' ),
            'new_item'              => __( 'New Template' ),
            'edit_item'             => __( 'Edit Template' ),
            'update_item'           => __( 'Update Template' ),
            'view_item'             => __( 'View Template' ),
            'search_items'          => __( 'Search Templates' ),
            'not_found'             => __( 'No templates found' ),
            'not_found_in_trash'    => __( 'No templates found in Trash' ),
            'featured_image'        => __( 'Featured Image' ),
            'set_featured_image'    => __( 'Set featured image' ),
            'remove_featured_image' => __( 'Remove featured image' ),
            'use_featured_image'    => __( 'Use as featured image' ),
            'insert_into_item'      => __( 'Insert into template' ),
            'uploaded_to_this_item' => __( 'Uploaded to this template' ),
            'items_list'            => __( 'Templates list' ),
            'items_list_navigation' => __( 'Templates list navigation' ),
            'filter_items_list'     => __( 'Filter templates list' )
        );

        $args = array(
            'label'               => __( 'Email Template' ),
            'description'         => __( 'Templates for automated emails' ),
            'labels'              => $labels,
            'supports'            => array( 'editor', 'title' ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 70,
            'menu_icon'           => 'dashicons-email-alt',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'email_template',
            'map_meta_cap'        => true
        );
        //</editor-fold>

        register_post_type( 'email_template', $args );

    }

    add_action( 'init', 'smartcat\mail\register_template_post_type' );


    function disable_wsiwyg( $enabled ) {
        if( get_post_type() == 'email_template' ) {
            $enabled = false;
        }

        return $enabled;
    }

    add_filter( 'user_can_richedit', 'smartcat\mail\disable_wsiwyg' );

}
