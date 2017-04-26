<?php

namespace SmartcatSupport\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function start() {
        $this->plugin->add_api_subscriber( new ReportsMenuPage(
            array(
                'type'          => 'submenu',
                'parent_menu'   => 'ucare_support',
                'menu_slug'     => 'ucare_support',
                'page_title'    => __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
                'menu_title'    => __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
                'capability'    => 'manage_support',
                'tabs' => array(
                    'overview' => new ReportsOverviewTab( __( 'Overview', \SmartcatSupport\PLUGIN_ID ) )
                )

            ) )
        );
    }

    public function enqueue_scripts( $hook ) {
        if( strpos( $hook, 'ucare_support' ) > 0 ) {
            wp_enqueue_script( 'chartist',
                $this->plugin->url() . '/assets/lib/chartist/chartist.min.js', null, $this->plugin->version() );

            wp_enqueue_style( 'chartist',
                $this->plugin->url() . '/assets/lib/chartist/chartist.min.css', null, $this->plugin->version() );

            wp_enqueue_script( 'reports',
                $this->plugin->url() . '/assets/admin/reports.js', array( 'jquery' ), $this->plugin->version() );

            wp_enqueue_style( 'reports',
                $this->plugin->url() . '/assets/admin/reports.css', null, $this->plugin->version() );
        }
    }

    public function subscribed_hooks() {
        return array(
            'admin_enqueue_scripts' => array( 'enqueue_scripts' )
        );
    }

}