<?php

namespace SmartcatSupport\admin;


use smartcat\admin\TabbedMenuPage;

class ReportsMenuPage extends TabbedMenuPage {

    public function __construct() {

        parent::__construct( array(
            'type'          => 'submenu',
            'parent_menu'   => 'ucare_support',
            'menu_slug'     => 'ucare_support',
            'page_title'    => __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            'menu_title'    => __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            'capability'    => 'manage_support',
            'tabs'          => array( 'overview' => new ReportsOverviewTab() )

        ) );

    }

    public function subscribed_hooks() {
        return array( 'support_menu_register' => array( 'register_page', 1 ) );
    }
}