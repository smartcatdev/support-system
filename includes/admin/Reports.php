<?php

namespace SmartcatSupport\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function start() {
        $this->plugin->add_api_subscriber( apply_filters( 'support_reporting_menupage', new ReportsMenuPage() ) );
    }

    public function enqueue_scripts( $hook ) {
        if( strpos( $hook, 'ucare_support' ) > 0 ) {
            wp_enqueue_script( 'moment',
                $this->plugin->url() . '/assets/lib/moment/moment.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'chartist',
                $this->plugin->url() . '/assets/lib/chartist/chartist.min.js', null, $this->plugin->version() );

            wp_enqueue_style( 'chartist',
                $this->plugin->url() . '/assets/lib/chartist/chartist.min.css', null, $this->plugin->version() );

            wp_enqueue_script( 'chartist-axistitle',
                $this->plugin->url() . '/assets/lib/chartist/plugins/chartist-plugin-axistitle.min.js', null, $this->plugin->version() );

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