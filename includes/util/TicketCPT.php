<?php

namespace SmartcatSupport\util;

use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\agents_dropdown;
use SmartcatSupport\form\field\SelectBox;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use function SmartcatSupport\products_dropdown;
use const SmartcatSupport\TEXT_DOMAIN;

class TicketCPT extends ActionListener {
    public function __construct() {

        $this->add_actions();
    }

    private function add_actions() {
        $this->add_action( 'init', 'register_post_type' );

        $this->add_action( 'restrict_manage_posts', 'post_filters' );
        $this->add_action( 'parse_query', 'filter_posts' );

        $this->add_action( 'manage_support_ticket_posts_columns', 'post_columns' );
        $this->add_action( 'manage_edit-support_ticket_sortable_columns', 'sortable_columns' );
        $this->add_action( 'manage_support_ticket_posts_custom_column', 'post_column_data', 10, 2 );
    }

    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Support Tickets', 'Post Type General Name', TEXT_DOMAIN ),
            'singular_name'         => _x( 'Support Ticket', 'Post Type Singular Name', TEXT_DOMAIN ),
            'menu_name'             => __( 'Support Tickets', TEXT_DOMAIN ),
            'name_admin_bar'        => __( 'Support Tickets', TEXT_DOMAIN ),
            'archives'              => __( 'Item Archives', TEXT_DOMAIN ),
            'parent_item_colon'     => __( 'Parent Item:', TEXT_DOMAIN ),
            'all_items'             => __( 'All Tickets', TEXT_DOMAIN ),
            'add_new_item'          => __( 'Add New Ticket', TEXT_DOMAIN ),
            'add_new'               => __( 'Add New', TEXT_DOMAIN ),
            'new_item'              => __( 'New Ticket', TEXT_DOMAIN ),
            'edit_item'             => __( 'Edit Ticket', TEXT_DOMAIN ),
            'update_item'           => __( 'Update Ticket', TEXT_DOMAIN ),
            'view_item'             => __( 'View Ticket', TEXT_DOMAIN ),
            'search_items'          => __( 'Search Ticket', TEXT_DOMAIN ),
            'not_found'             => __( 'Not found', TEXT_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
            'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
            'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
            'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
            'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
            'insert_into_item'      => __( 'Insert into ticket', TEXT_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this ticket', TEXT_DOMAIN ),
            'items_list'            => __( 'Tickets list', TEXT_DOMAIN ),
            'items_list_navigation' => __( 'Tickets list navigation', TEXT_DOMAIN ),
            'filter_items_list'     => __( 'Filter tickets list', TEXT_DOMAIN )
        );

        $capabilities = array();

        $args = array(
            'label'               => __( 'Support Ticket', TEXT_DOMAIN ),
            'description'         => __( 'Tickets for support requests', TEXT_DOMAIN ),
            'labels'              => $labels,
            'supports'            => array( 'editor', 'author', 'comments', 'title' ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 70,
            'menu_icon'           => 'dashicons-sos',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capabilities'        => $capabilities
        );

        register_post_type( 'support_ticket', $args );
    }

    public function post_columns( $columns ) {
        $cb = array_splice( $columns, 0, 1 );
        unset( $columns['title'] );
        unset( $columns['author'] );

        return array_merge(
            $cb + array(
                'id'       => __( 'Case #', TEXT_DOMAIN ),
                'title'    => __( 'Subject', TEXT_DOMAIN ),
                'product'  => __( 'Product', TEXT_DOMAIN ),
                'email'    => __( 'Email', TEXT_DOMAIN ),
                'status'   => __( 'Status', TEXT_DOMAIN ),
                'priority' => __( 'Priority', TEXT_DOMAIN ),
                'assigned' => __( 'Assigned', TEXT_DOMAIN )
            ),
            $columns
        );
    }

    public function post_column_data( $column, $post_id ) {
        switch ( $column ) {
            case 'id':
                echo $post_id;
                break;

            case 'email':
                esc_html_e( get_post_meta( $post_id, 'email', true ) );
                break;

            case 'product':
                $products = get_products();
                $value = get_post_meta( $post_id, 'product', true ) ;

                if( !empty( $products ) && array_key_exists( $value, $products ) ) {
                    echo $products[ $value ];
                }

                break;

            case 'assigned':
                $agents = get_agents();
                $value = get_post_meta( $post_id, 'agent', true );

                if( !empty( $agents ) && array_key_exists( $value, $agents ) ) {
                    echo $agents[ $value ];
                }

                break;

            case 'status':
                $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );

                ( new SelectBox( 'status',
                    array(
                        'data_attrs' => array( 'post_id' => $post_id ),
                        'value'      => get_post_meta( $post_id, 'status', true ),
                        'options'    => $statuses,
                        'class'      => 'admin-control'
                    )
                ) )->render();

                break;

            case 'priority':
                $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

                ( new SelectBox( 'priority',
                    array(
                        'data_attrs' => array( 'post_id' => $post_id ),
                        'value'      => get_post_meta( $post_id, 'priority', true ),
                        'options'    => $priorities,
                        'class'      => 'admin-control'
                    )
                ) )->render();

                break;

        }
    }

    public function sortable_columns( $columns ) {
        return array_merge(
            array(
                'status'   => 'status',
                'priority' => 'priority',
                'author'   => 'author'
            ),
            $columns
        );
    }

    public function post_filters() {
        if ( get_current_screen()->post_type == 'support_ticket' ) {
            agents_dropdown( 'agent', ! empty( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : '' );
            products_dropdown( 'product', ! empty( $_REQUEST['product'] ) ? $_REQUEST['product'] : '');
        }
    }

    public function filter_posts( $query ) {
        if ( ( ! empty( $GLOBALS['typenow'] ) && ! empty( $GLOBALS['pagenow'] ) ) &&
             ( $GLOBALS['typenow'] == 'support_ticket' && $GLOBALS['pagenow'] == 'edit.php' )
        ) {

            if ( ! empty( $_REQUEST['agent'] ) ) {
                $query->query_vars['meta_query'][] = array( 'key' => 'agent', 'value' => $_REQUEST['agent'] );
            }

            if ( ! empty( $_REQUEST['product'] ) ) {
                $query->query_vars['meta_query'][] = array( 'key' => 'product', 'value' => $_REQUEST['product'] );
            }

        }

        return $query;
    }
}
