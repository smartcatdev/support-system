<?php

namespace ucare\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function enqueue_scripts( $hook ) {
        if( strpos( $hook, 'ucare_support' ) > 0 ) {
            wp_enqueue_script( 'moment',
                $this->plugin->url() . '/assets/lib/moment/moment.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'flot',
                $this->plugin->url() . '/assets/lib/flot/jquery.flot.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'flot-time',
                $this->plugin->url() . '/assets/lib/flot/jquery.flot.time.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'flot-resize',
                $this->plugin->url() . '/assets/lib/flot/jquery.flot.resize.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'moment',
                $this->plugin->url() . '/assets/lib/moment/moment.min.js', null, $this->plugin->version() );

            wp_enqueue_script( 'ucare-reports-js',
                $this->plugin->url() . '/assets/admin/reports.js', null, $this->plugin->version() );

            wp_enqueue_style( 'ucare-reports-css',
                $this->plugin->url() . '/assets/admin/reports.css', null, $this->plugin->version() );
        }
    }

    public function subscribed_hooks() {
        return array(
            'admin_enqueue_scripts' => array( 'enqueue_scripts' )
        );
    }

}