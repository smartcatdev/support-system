<?php

namespace SmartcatSupport\admin;


use smartcat\admin\TabbedMenuPage;

class ReportsMenuPage extends TabbedMenuPage {
    public function subscribed_hooks() {
        return array( 'support_menu_register' => array( 'register_page', 1 ) );
    }
}