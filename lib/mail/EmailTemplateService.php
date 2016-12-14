<?php

namespace smartcat\mail;

if( !class_exists( '\smartcat\mail\EmailTemplateService' ) ) :

class EmailTemplateService {

    private $required_by;
    private $text_domain;

    private static $instance;

    protected function __construct( $required_by, $text_domain ) {
        $this->required_by = $required_by;
        $this->text_domain = $text_domain;
    }

    private function add_actions() {
        add_action( 'tgmpa_register', array( $this, 'register_dependencies' ) );
        add_action( 'init', array( $this, 'register_template_cpt' ) );
        add_action( 'smartcat_send_mail', array( $this, 'send_template' ), 10, 2 );
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
            'id'           => 'smartcat_required_plugins',
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
                'notice_can_install_required'    => _n_noop(
                /* translators: 1: plugin name(s). */
                    $this->required_by . ' requires the following plugin: %1$s.',
                    $this->required_by . ' requires the following plugins: %1$s.',
                    $this->text_domain
                ),
                'notice_can_install_recommended' => _n_noop(
                /* translators: 1: plugin name(s). */
                    $this->required_by . ' recommends the following plugin: %1$s.',
                    $this->required_by . ' recommends the following plugins: %1$s.',
                    $this->text_domain
                ),
            )
        );

        tgmpa( $plugins, $config );
    }

    public function register_template_cpt() {
        $labels = array(
            'name'                  => _x( 'Email Templates', 'Post Type General Name', $this->text_domain ),
            'singular_name'         => _x( 'Email Template', 'Post Type Singular Name',$this->text_domain ),
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

        $capabilities = array();

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
            'capabilities'        => $capabilities
        );

        register_post_type( 'email_template', $args );
    }

    public function send_template( $template_id, $recipient ) {
        $template = get_post( $template_id );

        if( !empty( $template ) ) {
            wp_mail(
                $recipient,
                $template->post_title,
                $template->post_content,
                array( 'Content-Type: text/html; charset=UTF-8' )
            );
        }
    }

    public static function register( $plugin_name, $text_domain ) {
        if( empty( self::$instance ) ) {
            self::$instance = new self( $plugin_name, $text_domain );

            require_once dirname( dirname( __FILE__ ) ) . '/tgm/TGM_Plugin_Activation.php';

            self::$instance->add_actions();
        }
    }

    public static function template_dropdown_list() {
        $templates = array();

        $query = new \WP_Query(
            array(
                'post_type'   => 'email_template',
                'post_status' => 'publish'
            )
        );

        if( $query->have_posts() ) {
            foreach ( $query->posts as $template ) {
                $templates [ $template->ID ] = $template->post_title;
            }
        }

        return $templates;
    }
}

endif;
