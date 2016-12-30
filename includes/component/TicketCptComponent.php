<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\core\HookSubscriber;
use const SmartcatSupport\PLUGIN_NAME;

class TicketCptComponent extends AbstractComponent implements HookSubscriber {

    public function start() {
        $this->plugin->add_api_subscriber( $this );
    }

    public function register_cpt() {
        $labels = array(
            'name'                  => _x( 'Support Tickets', 'Post Type General Name', PLUGIN_NAME ),
            'singular_name'         => _x( 'Support Ticket', 'Post Type Singular Name', PLUGIN_NAME ),
            'menu_name'             => __( 'Support Tickets', PLUGIN_NAME ),
            'name_admin_bar'        => __( 'Support Tickets', PLUGIN_NAME ),
            'archives'              => __( 'Item Archives', PLUGIN_NAME ),
            'parent_item_colon'     => __( 'Parent Item:', PLUGIN_NAME ),
            'all_items'             => __( 'All Tickets', PLUGIN_NAME ),
            'add_new_item'          => __( 'New Ticket', PLUGIN_NAME ),
            'add_new'               => __( 'New Ticket', PLUGIN_NAME ),
            'new_item'              => __( 'New Ticket', PLUGIN_NAME ),
            'edit_item'             => __( 'Edit Ticket', PLUGIN_NAME ),
            'update_item'           => __( 'Update Ticket', PLUGIN_NAME ),
            'view_item'             => __( 'View Ticket', PLUGIN_NAME ),
            'search_items'          => __( 'Search Ticket', PLUGIN_NAME ),
            'not_found'             => __( 'Ticket Not found', PLUGIN_NAME ),
            'not_found_in_trash'    => __( 'Ticket Not found in Trash', PLUGIN_NAME ),
            'featured_image'        => __( 'Featured Image', PLUGIN_NAME ),
            'set_featured_image'    => __( 'Set featured image', PLUGIN_NAME ),
            'remove_featured_image' => __( 'Remove featured image', PLUGIN_NAME ),
            'use_featured_image'    => __( 'Use as featured image', PLUGIN_NAME ),
            'insert_into_item'      => __( 'Insert into ticket', PLUGIN_NAME ),
            'uploaded_to_this_item' => __( 'Uploaded to this ticket', PLUGIN_NAME ),
            'items_list'            => __( 'Tickets list', PLUGIN_NAME ),
            'items_list_navigation' => __( 'Tickets list navigation', PLUGIN_NAME ),
            'filter_items_list'     => __( 'Filter tickets list', PLUGIN_NAME )
        );

        $capabilities = array();

        $args = array(
            'label'               => __( 'Support Ticket', PLUGIN_NAME ),
            'description'         => __( 'Tickets for support requests', PLUGIN_NAME ),
            'labels'              => $labels,
            'supports'            => array( 'editor', 'comments', 'title' ),
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

    public function cleanup() {
        unregister_post_type( 'support_ticket' );
    }

    public function subscribed_hooks() {
        return array(
            'init' => 'register_cpt',
            $this->plugin->name() . '_cleanup' => 'cleanup'
        );
    }
}
