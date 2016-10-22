<?php

namespace SmartcatSupport\util;

use SmartcatSupport\desc\Option;
use SmartcatSupport\abstracts\ActionListener;
use const SmartcatSupport\TEXT_DOMAIN;
use const SmartcatSupport\PLUGIN_VERSION;

/**
 *  Installs plugin components 
 * 
 *  @author Eric Green <eric@smartcat.ca>
 *  @since 1.0.0
 */
final class Installer extends ActionListener {
    
    public function __construct() {
        $this->add_action( 'init', 'register_post_type' );
    }
    
    public function activate() {
        update_option( Option::PLUGIN_VERSION, PLUGIN_VERSION );
        
        $this->add_user_roles();
    }
    
    public function deactivate() {
        unregister_post_type( 'support_ticket' );
        
        $this->remove_user_roles();
    }
    
    /**
     * Register the ticket custom post type
     */
    public function register_post_type() {
        $labels = [
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
            'view_item'             => __( 'ViewTicket', TEXT_DOMAIN ),
            'search_items'          => __( 'Search Ticket', TEXT_DOMAIN ),
            'not_found'             => __( 'Not found', TEXT_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
            'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
            'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
            'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN),
            'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
            'insert_into_item'      => __( 'Insert into ticket', TEXT_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this ticket', TEXT_DOMAIN ),
            'items_list'            => __( 'Tickets list', TEXT_DOMAIN ),
            'items_list_navigation' => __( 'Tickets list navigation', TEXT_DOMAIN ),
            'filter_items_list'     => __( 'Filter tickets list', TEXT_DOMAIN )
	];
        
	$capabilities = [];
        
	$args = [
            'label'                 => __( 'Support Ticket', TEXT_DOMAIN ),
            'description'           => __( 'Tickets for support requests', TEXT_DOMAIN ),
            'labels'                => $labels,
            'supports'              => array( 'editor', 'author', 'comments', 'title' ),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 70,
            'menu_icon'             => 'dashicons-sos',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,		
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capabilities'          => $capabilities
	];
        
	register_post_type( 'support_ticket', $args );
    }
    
    public function add_user_roles() {
        add_role( 'support_agent', __( 'Support Agent', TEXT_DOMAIN ), [ 'edit_others_tickets' => true, 'edit_ticket_meta' => true ] );
        add_role( 'support_user', __( 'Support User', TEXT_DOMAIN ), [ 'edit_tickets' => true ] );
    }
    
    public function remove_user_roles() {
        remove_role( 'support_agent' );
        remove_role( 'support_user' );
    }
}
