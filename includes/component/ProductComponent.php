<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\core\HookSubscriber;
use SmartcatSupport\descriptor\Option;

class ProductComponent extends AbstractComponent implements HookSubscriber {

    public function start() {
        $this->plugin->add_api_subscriber( $this );
    }

    public function list_products( $products ) {
        $results = array();

        if( $this->plugin->woo_active && get_option( Option::WOO_INTEGRATION ) == 'on' ) {
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

        if( $this->plugin->edd_active && get_option( Option::EDD_INTEGRATION ) == 'on' ) {
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

        return array_merge( $products, $results );
    }

    public function configure_customer_caps( $val ) {
        if( $this->plugin->woo_active ) {
            if( $val == 'on' ) {
                $this->plugin->add_caps( get_role('customer') );
            } else {
                $this->plugin->remove_caps( get_role('customer') );
            }
        }

        return $val;
    }

    public function configure_subscriber_caps( $val ) {
        if( $this->plugin->edd_active ) {
            if ($val == 'on') {
                $this->plugin->add_caps( get_role( 'subscriber' ) );
            } else {
                $this->plugin->remove_caps( get_role( 'subscriber' ) );
            }
        }

        return $val;
    }

    public function subscribed_hooks() {
        return array(
            'support_list_products' => array( 'list_products' ),
            'pre_update_option_' . Option::EDD_INTEGRATION => array( 'configure_subscriber_caps' ),
            'pre_update_option_' . Option::WOO_INTEGRATION => array( 'configure_customer_caps' ),
        );
    }
}