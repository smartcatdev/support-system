<?php

use smartcat\admin\SettingsPage;
use smartcat\admin\SettingsSection;
use smartcat\admin\TextField;
use SmartcatSupport\descriptor\Option;
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

$section = new SettingsSection( 'strings', __( 'Label Text', TEXT_DOMAIN ) );

$section->add_field( new TextField(
    array(
        'id'  => Option::LOGIN_DISCLAIMER,
        'value' => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label' => __( 'Login Disclaimer', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::REGISTER_BTN_TEXT,
        'value' => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label' => __( 'Register Button Label', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::LOGIN_BTN_TEXT,
        'value' => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label' => __( 'Login Button Label', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::CREATE_BTN_TEXT,
        'value' => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label' => __( 'Create Ticket Button Label', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::CANCEL_BTN_TEXT,
        'value' => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label' => __( 'Cancel Operation Button Label', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::TICKET_CREATED_MSG,
        'value' => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label' => __( 'Ticket Created Message', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::TICKET_UPDATED_MSG,
        'value' => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label' => __( 'Ticket Updated Message', TEXT_DOMAIN )
    )
) )->add_field( new TextField(
    array(
        'id'  => Option::EMPTY_TABLE_MSG,
        'value' => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label' => __( 'No Tickets Message', TEXT_DOMAIN )
    )
) );

$admin->add_section( $section );

$admin->register();