<?php

use smartcat\admin\CheckBoxField;
use smartcat\admin\MatchFilter;
use smartcat\admin\SelectBoxField;
use smartcat\admin\SettingsSection;
use smartcat\admin\TabbedSettingsPage;
use smartcat\admin\TextField;
use smartcat\admin\TextFilter;
use smartcat\mail\EmailTemplateService;
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
            'general'     => __( 'General', TEXT_DOMAIN ),
            'display'     => __( 'Display', TEXT_DOMAIN ),
            'email'       => __( 'Email', TEXT_DOMAIN ),
            'advanced'    => __( 'Advanced', TEXT_DOMAIN )
        )
    )
);

$text = new SettingsSection( 'text', __( 'Text & Labels', TEXT_DOMAIN ) );

$text->add_field( new TextField(
    array(
        'id'            => 'support_login_disclaimer',
        'option'        => Option::LOGIN_DISCLAIMER,
        'value'         => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label'         => __( 'Login Disclaimer', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_register_btn_text',
        'option'        => Option::REGISTER_BTN_TEXT,
        'value'         => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label'         => __( 'Register Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_btn_text',
        'option'        => Option::LOGIN_BTN_TEXT,
        'value'         => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label'         => __( 'Login Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_create_btn_text',
        'option'        => Option::CREATE_BTN_TEXT,
        'value'         => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label'         => __( 'Create Ticket Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_cancel_btn_text',
        'option'        => Option::CANCEL_BTN_TEXT,
        'value'         => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label'         => __( 'Cancel Operation Button Label', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_created_msg',
        'option'        => Option::TICKET_CREATED_MSG,
        'value'         => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label'         => __( 'Ticket Created Message', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_updated_msg',
        'option'        => Option::TICKET_UPDATED_MSG,
        'value'         => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label'         => __( 'Ticket Updated Message', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_empty_table_msg',
        'option'        => Option::EMPTY_TABLE_MSG,
        'value'         => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label'         => __( 'No Tickets Message', TEXT_DOMAIN ),
        'validators'    => array( new TextFilter() )
    )
) );

$general = new SettingsSection( 'general', __( 'General Settings', TEXT_DOMAIN ) );

$general->add_field( new TextField(
    array(
        'id'            => 'support_login_logo',
        'option'        => Option::LOGIN_LOGO,
        'value'         => get_option( Option::LOGIN_LOGO, Option\Defaults::LOGIN_LOGO ),
        'label'         => __( 'Login Logo Image', TEXT_DOMAIN )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_allow_signups',
        'option'        => Option::ALLOW_SIGNUPS,
        'value'         => get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ),
        'label'         => __( 'Allow users to signup', TEXT_DOMAIN ),
        'desc'          => __( 'Allow users to create accounts for submitting tickets', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_edd_integration',
        'option'        => Option::EDD_INTEGRATION,
        'value'         => get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ),
        'label'         => __( 'Easy Digital Downloads', TEXT_DOMAIN ),
        'desc'          => __( 'Enable integration with Easy Digital Downloads', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_woo_integration',
        'option'        => Option::WOO_INTEGRATION,
        'value'         => get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ),
        'label'         => __( 'WooCommerce', TEXT_DOMAIN ),
        'desc'          => __( 'Enable integration with WooCommerce', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$email = new SettingsSection( 'email', __( 'Email Templates', TEXT_DOMAIN ) );

$email->add_field( new SelectBoxField(
    array(
        'id'            => 'support_welcome_email_template',
        'option'        => Option::WELCOME_EMAIL_TEMPLATE,
        'value'         => get_option( Option::WELCOME_EMAIL_TEMPLATE ),
        'options'       => EmailTemplateService::template_dropdown_list(),
        'label'         => __( 'Welcome Email Template', TEXT_DOMAIN ),
        'desc'          => __( 'The email template to be sent out after registration', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array_keys( EmailTemplateService::template_dropdown_list() ), '' ) )
    )
) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_closed_email_template',
        'option'        => Option::CLOSED_EMAIL_TEMPLATE,
        'value'         => get_option( Option::CLOSED_EMAIL_TEMPLATE ),
        'options'       => EmailTemplateService::template_dropdown_list(),
        'label'         => __( 'Ticket Closed Email Template', TEXT_DOMAIN ),
        'desc'          => __( 'The email template to be sent out after a ticket has been closed', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array_keys( EmailTemplateService::template_dropdown_list() ), '' ) )
    )
) );

$advanced = new SettingsSection( 'advanced', __( 'CAUTION: Some of these may bite', TEXT_DOMAIN ) );

$advanced->add_field( new CheckBoxField(
    array(
        'id'            => 'support_nuke_data',
        'option'        => Option::NUKE,
        'value'         => get_option( Option::NUKE, Option\Defaults::NUKE ),
        'label'         => __( 'Erase All Data', TEXT_DOMAIN ),
        'desc'          => __( 'Erase all data on plugin uninstall', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_restore_template',
        'option'        => Option::RESTORE_TEMPLATE_PAGE,
        'value'         => '',
        'label'         => __( 'Restore Template Page', TEXT_DOMAIN ),
        'desc'          => __( 'Restore the template page if its been deleted', TEXT_DOMAIN ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$admin->add_section( $general, 'general' );
$admin->add_section( $email, 'email' );
$admin->add_section( $advanced, 'advanced' );
$admin->add_section( $text, 'display' );


$admin->register();