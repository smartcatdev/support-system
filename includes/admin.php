<?php

use smartcat\admin\SettingsPage;
use smartcat\admin\SettingsSection;
use const SmartcatSupport\TEXT_DOMAIN;

$admin = new SettingsPage(
    array(
        'type'          => 'submenu',
        'parent_menu'   => 'edit.php?post_type=support_ticket',
        'page_title'    => __( 'Support Settings', TEXT_DOMAIN ),
        'menu_title'    => __( 'Settings', TEXT_DOMAIN ),
        'menu_slug'     => 'support-options',
    )
);

$section = new SettingsSection( 'general', __( 'General', TEXT_DOMAIN ) );

$admin->add_section( $section );

$admin->register();