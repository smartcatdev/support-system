<?php

namespace ucare\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function enqueue_scripts( $hook ) {
        if( strpos( $hook, 'ucare_support' ) > 0 ) {
            wp_enqueue_script( 'moment',
                $this->plugin->url() . '/assets/lib/moment/moment.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'chartist',
                $this->plugin->url() . '/assets/lib/chartist/chartist.min.js', null, $this->plugin->version() );

            wp_enqueue_style( 'chartist',
                $this->plugin->url() . '/assets/lib/chartist/chartist.min.css', null, $this->plugin->version() );

            wp_enqueue_script( 'chartist-legend',
                $this->plugin->url() . '/assets/lib/chartist/plugins/chartist-plugin-legend.js', null, $this->plugin->version() );

            wp_enqueue_script( 'chartist-tooltip',
                $this->plugin->url() . '/assets/lib/chartist/plugins/chartist-plugin-tooltip.min.js', null, $this->plugin->version() );

            wp_enqueue_style( 'chartist-tooltip',
                $this->plugin->url() . '/assets/lib/chartist/plugins/chartist-plugin-tooltip.css', null, $this->plugin->version() );

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