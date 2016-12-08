<?php

use smartcat\admin\CheckBoxField;
use smartcat\admin\MatchFilter;
use smartcat\admin\SettingsSection;
use smartcat\admin\TabbedSettingsPage;
use smartcat\admin\TextField;
use smartcat\admin\TextFilter;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\TEXT_DOMAIN;

$admin = new TabbedSettingsPage(
    array(
        'type'          => 'submenu',
        'parent_menu'   => 'edit.php?post_type=support_ticket',
        'page_title'    => __( 'Support Settings', TEXT_DOMAIN ),
        'menu_title'    => __( 'Settings', TEXT_DOMAIN ),
        'menu_slug'     => 'support_options',
        'tabs'          => array(
            'general'       => __( 'General', TEXT_DOMAIN ),
            'text'        => __( 'Text', TEXT_DOMAIN )
        )
    )
);

$text = new SettingsSection( 'text', __( 'Text', TEXT_DOMAIN ) );

$text->add_field( new TextField(
    array(
        'id'            => Option::LOGIN_DISCLAIMER,
        'value'         => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label'         => __( 'Login Disclaimer', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::REGISTER_BTN_TEXT,
        'value'         => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label'         => __( 'Register Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::LOGIN_BTN_TEXT,
        'value'         => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label'         => __( 'Login Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::CREATE_BTN_TEXT,
        'value'         => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label'         => __( 'Create Ticket Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::CANCEL_BTN_TEXT,
        'value'         => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label'         => __( 'Cancel Operation Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::TICKET_CREATED_MSG,
        'value'         => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label'         => __( 'Ticket Created Message', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::TICKET_UPDATED_MSG,
        'value'         => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label'         => __( 'Ticket Updated Message', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => Option::EMPTY_TABLE_MSG,
        'value'         => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label'         => __( 'No Tickets Message', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )
) );

$general = new SettingsSection( 'general', __( 'General', TEXT_DOMAIN ) );

$general->add_field( new CheckBoxField(
    array(
        'id'            => Option::ALLOW_SIGNUPS,
        'value'         => get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ),
        'label'         => __( 'Allow users to signup', TEXT_DOMAIN ),
        'desc'          => __( 'Allow users to create accounts for submitting tickets', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => Option::EDD_INTEGRATION,
        'value'         => get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ),
        'label'         => __( 'Easy Digital Downloads', TEXT_DOMAIN ),
        'desc'          => __( 'Enable integration with Easy Digital Downloads', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => Option::WOO_INTEGRATION,
        'value'         => get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ),
        'label'         => __( 'WooCommerce', TEXT_DOMAIN ),
        'desc'          => __( 'Enable integration with WooCommerce', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$admin->add_section( $general, 'general' );
$admin->add_section( $text, 'text' );


$admin->register();