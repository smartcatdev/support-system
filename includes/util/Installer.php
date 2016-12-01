<?php

namespace SmartcatSupport\util;

use function SmartcatSupport\agents_dropdown;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\TEXT_DOMAIN;
use const SmartcatSupport\PLUGIN_VERSION;

/**
 *  Installs plugin components 
 * 
 *  @author Eric Green <eric@smartcat.ca>
 *  @since 1.0.0
 */
final class Installer extends ActionListener {

    public function activate() {
        update_option( Option::PLUGIN_VERSION, PLUGIN_VERSION );

        $this->register_page();
        $this->add_user_roles();
    }
    
    public function deactivate() {
        unregister_post_type( 'support_ticket' );

        $this->remove_user_roles();
    }

    public function add_user_roles() {
        add_role( 'support_admin', __( 'Support Admin', TEXT_DOMAIN ), array(
            'view_support_tickets'      => true,
            'create_support_tickets'    => true,
            'unfiltered_html'           => true,
            'edit_others_tickets'       => true
        ) );

        add_role( 'support_agent', __( 'Support Agent', TEXT_DOMAIN ), array(
            'view_support_tickets'      => true,
            'unfiltered_html'           => true,
            'edit_others_tickets'       => true
        ) );

        add_role( 'support_user', __( 'Support User', TEXT_DOMAIN ), array(
            'view_support_tickets'      => true,
            'create_support_tickets'    => true,
            'unfiltered_html'           => true
        ) );

        $role = get_role( 'administrator' );
        $role->add_cap( 'view_support_tickets' );
        $role->add_cap( 'unfiltered_html' );
        $role->add_cap( 'edit_others_tickets' );
        $role->add_cap( 'create_support_tickets' );
    }
    
    public function remove_user_roles() {
        $role = get_role( 'administrator' );
        $role->remove_cap( 'view_support_tickets' );
        $role->remove_cap( 'unfiltered_html' );
        $role->remove_cap( 'edit_others_tickets' );
        $role->remove_cap( 'create_support_tickets' );

        remove_role( 'support_admin' );
        remove_role( 'support_agent' );
        remove_role( 'support_user' );
    }

    public function register_page() {
        $post_id = null;
        $post = get_post( get_option( Option::TEMPLATE_PAGE_ID ) ) ;

        if( empty( $post ) ) {
            $post_id = wp_insert_post(
                array(
                    'post_type' =>  'page',
                    'post_status' => 'publish',
                    'post_title' => __( 'Support', TEXT_DOMAIN )
                )
            );
        } else {
            $post_id = $post->ID;
        }

        if( !empty( $post_id ) ) {
            update_option( Option::TEMPLATE_PAGE_ID, $post_id );
        }
    }
}
