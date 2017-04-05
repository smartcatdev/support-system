<?php

namespace smartcat\mail;

use smartcat\core\HookRegisterer;
use smartcat\core\HookSubscriber;

if( !class_exists( '\smartcat\mail\Mailer' ) ) :

class Mailer implements HookSubscriber  {

    private static $instance;

    private $metabox;
    private $text_domain;

    public static function init( HookRegisterer $plugin ) {
        if( empty( self::$instance ) ) {
            self::$instance = new self();
            self::configure_caps();

            $plugin->add_api_subscriber( self::$instance );
            $plugin->add_api_subscriber( self::$instance->metabox );
        }

        return self::$instance;
    }

    private function __construct() {
        $this->text_domain = apply_filters( 'mailer_text_domain', '' );

        $this->metabox = new StyleMetabox( array(
            'id'        => 'mailer_meta',
            'title'     => __( 'Template Style Sheet', $this->text_domain ),
            'post_type' => 'email_template',
            'context'   => 'advanced',
            'priority'  => 'high'
        ) );
    }

    public function replace_default_vars( $content, $recipient, $template ) {
        $user = get_user_by( 'email', $recipient );

        if( !empty( $user ) ) {
            $content = str_replace( '{%username%}', $user->user_login, $content );
            $content = str_replace( '{%first_name%}', $user->first_name, $content );
            $content = str_replace( '{%last_name%}', $user->last_name, $content );
            $content = str_replace( '{%full_name%}', $user->first_name . ' ' . $user->last_name, $content );
        }

        $content = str_replace( '{%template_title%}', $template->post_title, $content );
        $content = str_replace( '{%email%}', !empty( $user ) ? $user->user_email : $recipient, $content );
        $content = str_replace( '{%home_url%}', home_url(), $content );

        return $content;
    }

    public function register_template_cpt() {
        //<editor-fold desc="$args array">
        $labels = array(
            'name'                  => _x( 'Email Templates', 'Post Type General Name', $this->text_domain ),
            'singular_name'         => _x( 'Email Template', 'Post Type Singular Name', $this->text_domain ),
            'menu_name'             => __( 'Email Templates', $this->text_domain ),
            'name_admin_bar'        => __( 'Email Templates', $this->text_domain ),
            'archives'              => __( 'Template Archives', $this->text_domain ),
            'parent_item_colon'     => __( 'Parent Item:', $this->text_domain ),
            'all_items'             => __( 'All Templates', $this->text_domain ),
            'add_new_item'          => __( 'New Template', $this->text_domain ),
            'add_new'               => __( 'New Template', $this->text_domain ),
            'new_item'              => __( 'New Template', $this->text_domain ),
            'edit_item'             => __( 'Edit Template', $this->text_domain ),
            'update_item'           => __( 'Update Template', $this->text_domain ),
            'view_item'             => __( 'View Template', $this->text_domain ),
            'search_items'          => __( 'Search Templates', $this->text_domain ),
            'not_found'             => __( 'No templates found', $this->text_domain ),
            'not_found_in_trash'    => __( 'No templates found in Trash', $this->text_domain ),
            'featured_image'        => __( 'Featured Image', $this->text_domain ),
            'set_featured_image'    => __( 'Set featured image', $this->text_domain ),
            'remove_featured_image' => __( 'Remove featured image', $this->text_domain ),
            'use_featured_image'    => __( 'Use as featured image', $this->text_domain ),
            'insert_into_item'      => __( 'Insert into template', $this->text_domain ),
            'uploaded_to_this_item' => __( 'Uploaded to this template', $this->text_domain ),
            'items_list'            => __( 'Templates list', $this->text_domain ),
            'items_list_navigation' => __( 'Templates list navigation', $this->text_domain ),
            'filter_items_list'     => __( 'Filter templates list', $this->text_domain )
        );

        $args = array(
            'label'               => __( 'Email Template', $this->text_domain ),
            'description'         => __( 'Templates for automated emails', $this->text_domain ),
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

    public static function send_template( $template_id, $recipient ) {
        $template = get_post( $template_id );
        $content = apply_filters( 'parse_email_template', $template->post_content, $recipient, $template );
        $sent = false;

        if( !empty( $template ) ) {
            $sent = wp_mail(
                $recipient,
                $template->post_title,
                self::$instance->wrap_template( $template, $content ),
                array( 'Content-Type: text/html; charset=UTF-8' )
            );
        }

        return $sent;
    }

    public static function cleanup( $nuke = false ) {
        self::cleanup_caps();

        if( empty( apply_filters( 'mailer_consumers', array() ) ) ) {
            unregister_post_type( 'email_template' );

            if( $nuke ) {
                $query = new \WP_Query( array( 'post_type' => 'email_template' ) );

                foreach( $query->posts as $post ) {
                    wp_trash_post( $post->ID );
                }
            }
        }
    }

    public function disable_wysiwyg( $enabled ) {
        if( get_post_type() == 'email_template' ) {
            $enabled = false;
        }

        return $enabled;
    }

    public function subscribed_hooks() {
        return array(
            'init' => array( 'register_template_cpt' ),
            'user_can_richedit' => array( 'disable_wysiwyg' ),
            'parse_email_template' => array( 'replace_default_vars', 10, 3 )
        );
    }

    private function wrap_template( $template, $content ) {
        ob_start(); ?>

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
                </head>
                <body>
                    <?php echo $content; ?>
                    <div class="footer">
                        <p><?php echo do_action( 'email_template_footer', $template ); ?></p>
                    </div>
                </body>
            </html>

        <?php return ob_get_clean();
    }

    private static function configure_caps() {
        $administrator = get_role( 'administrator' );

        $administrator->add_cap( 'read_email_template' );
        $administrator->add_cap( 'read_email_templates' );
        $administrator->add_cap( 'edit_email_template' );
        $administrator->add_cap( 'edit_email_templates' );
        $administrator->add_cap( 'edit_others_email_templates' );
        $administrator->add_cap( 'edit_published_email_templates' );
        $administrator->add_cap( 'publish_email_templates' );
        $administrator->add_cap( 'delete_others_email_templates' );
        $administrator->add_cap( 'delete_private_email_templates' );
        $administrator->add_cap( 'delete_published_email_templates' );
    }

    private static function cleanup_caps() {
        $administrator = get_role( 'administrator' );

        $administrator->remove_cap( 'read_email_template' );
        $administrator->remove_cap( 'read_email_templates' );
        $administrator->remove_cap( 'edit_email_template' );
        $administrator->remove_cap( 'edit_email_templates' );
        $administrator->remove_cap( 'edit_others_email_templates' );
        $administrator->remove_cap( 'edit_published_email_templates' );
        $administrator->remove_cap( 'publish_email_templates' );
        $administrator->remove_cap( 'delete_others_email_templates' );
        $administrator->remove_cap( 'delete_private_email_templates' );
        $administrator->remove_cap( 'delete_published_email_templates' );
    }

    public static function list_templates() {
        global $wpdb;

        $results = array();
        $templates = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type='email_template' AND post_status='publish'" );

        foreach( $templates as $template ) {
            $results[ $template->ID ] = $template ->post_title;
        }

        return $results;
    }

}

endif;

