<?php

namespace SmartcatSupport\admin;

use smartcat\core\AbstractComponent;

class Reports extends AbstractComponent {

    public function register_menu_page() {
        add_submenu_page(
            'ucare_support',
            __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            'manage_support',
            'reports'

        );
    }

    public function subscribed_hooks() {
        return array(
            'admin_menu' => array( 'register_menu_page', 1, 0 )
        );
    }
}