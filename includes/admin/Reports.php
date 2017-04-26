<?php

namespace SmartcatSupport\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function register_menu_page() {
        add_submenu_page(
            'ucare_support',
            __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            'manage_options',
            'ucare_support',
            $this->dir . 'admin/reports.php'
        );
    }

    public function menu_page() {
        echo 'sdf';
    }

    public function subscribed_hooks() {
        return array(
            'support_menu_register' => array( 'register_menu_page', 1 )
        );
    }
}