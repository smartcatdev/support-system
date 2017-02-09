<?php

use smartcat\admin\CheckBoxField;
use smartcat\admin\MatchFilter;
use smartcat\admin\SelectBoxField;
use smartcat\admin\SettingsSection;
use smartcat\admin\TabbedSettingsPage;
use smartcat\admin\TextField;
use smartcat\admin\TextFilter;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

$admin = new TabbedSettingsPage(
    array(
        'type'          => 'submenu',
        'parent_menu'   => 'edit.php?post_type=support_ticket',
        'page_title'    => __( 'Support Settings', Plugin::ID ),
        'menu_title'    => __( 'Settings', Plugin::ID ),
        'menu_slug'     => 'support_options',
        'tabs'          => array(
            'general'       => __( 'General', Plugin::ID ),
            'display'       => __( 'Display', Plugin::ID ),
            'appearance'    => __( 'Appearance', Plugin::ID ),
            'notifications' => __( 'Notifications', Plugin::ID ),
            'advanced'      => __( 'Advanced', Plugin::ID )
        )
    )
);

$appearance = new SettingsSection( 'appearance', __( 'Appearance', Plugin::ID ) );

$appearance->add_field( new TextField(
    array(
        'id'            => 'support_primary_color',
        'option'        => Option::PRIMARY_COLOR,
        'value'         => get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ),
        'label'         => __( 'Primary color', Plugin::ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_hover_color',
        'option'        => Option::HOVER_COLOR,
        'value'         => get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ),
        'label'         => __( 'Hover color', Plugin::ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_secondary_color',
        'option'        => Option::SECONDARY_COLOR,
        'value'         => get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ),
        'label'         => __( 'Secondary color', Plugin::ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_tertiary_color',
        'option'        => Option::TERTIARY_COLOR,
        'value'         => get_option( Option::TERTIARY_COLOR, Option\Defaults::TERTIARY_COLOR ),
        'label'         => __( 'Tertiary color', Plugin::ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) );

$text = new SettingsSection( 'text', __( 'Text & Labels', Plugin::ID ) );

$text->add_field( new TextField(
    array(
        'id'            => 'support_login_disclaimer',
        'option'        => Option::LOGIN_DISCLAIMER,
        'value'         => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label'         => __( 'Login Disclaimer', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_register_btn_text',
        'option'        => Option::REGISTER_BTN_TEXT,
        'value'         => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label'         => __( 'Register Button Label', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_btn_text',
        'option'        => Option::LOGIN_BTN_TEXT,
        'value'         => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label'         => __( 'Login Button Label', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_create_btn_text',
        'option'        => Option::CREATE_BTN_TEXT,
        'value'         => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label'         => __( 'Create Ticket Button Label', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_cancel_btn_text',
        'option'        => Option::CANCEL_BTN_TEXT,
        'value'         => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label'         => __( 'Cancel Operation Button Label', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_created_msg',
        'option'        => Option::TICKET_CREATED_MSG,
        'value'         => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label'         => __( 'Ticket Created Message', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_updated_msg',
        'option'        => Option::TICKET_UPDATED_MSG,
        'value'         => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label'         => __( 'Ticket Updated Message', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_empty_table_msg',
        'option'        => Option::EMPTY_TABLE_MSG,
        'value'         => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label'         => __( 'No Tickets Message', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_comments_closed_msg',
        'option'        => Option::COMMENTS_CLOSED_MSG,
        'value'         => get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ),
        'label'         => __( 'Comments Closed Message', Plugin::ID ),
        'validators'    => array( new TextFilter() )
    )
) );

$general = new SettingsSection( 'general', __( 'General Settings', Plugin::ID ) );

$general->add_field( new TextField(
    array(
        'id'            => 'support_login_logo',
        'option'        => Option::LOGIN_LOGO,
        'value'         => get_option( Option::LOGIN_LOGO, Option\Defaults::LOGIN_LOGO ),
        'label'         => __( 'Login Logo Image', Plugin::ID )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_allow_signups',
        'option'        => Option::ALLOW_SIGNUPS,
        'value'         => get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ),
        'label'         => __( 'Allow users to signup', Plugin::ID ),
        'desc'          => __( 'Allow users to create accounts for submitting tickets', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_edd_integration',
        'option'        => Option::EDD_INTEGRATION,
        'value'         => get_option( Option::EDD_INTEGRATION, Option\Defaults::EDD_INTEGRATION ),
        'label'         => __( 'Easy Digital Downloads', Plugin::ID ),
        'desc'          => __( 'Enable integration with Easy Digital Downloads', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_woo_integration',
        'option'        => Option::WOO_INTEGRATION,
        'value'         => get_option( Option::WOO_INTEGRATION, Option\Defaults::WOO_INTEGRATION ),
        'label'         => __( 'WooCommerce', Plugin::ID ),
        'desc'          => __( 'Enable integration with WooCommerce', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$emails = new SettingsSection( 'email_templates', __( 'Email Templates', Plugin::ID ) );

$email_templates = array( '' => __( 'Select an email template', Plugin::ID ) ) + Mailer::list_templates();

$emails->add_field( new SelectBoxField(
    array(
        'id'            => 'support_welcome_email_template',
        'option'        => Option::WELCOME_EMAIL_TEMPLATE,
        'value'         => get_option( Option::WELCOME_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Welcome', Plugin::ID ),
        'desc'          => __( 'Template Variables: username, password, first_name, last_name, full_name, email', Plugin::ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )
) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_resolved_email_template',
        'option'        => Option::RESOLVED_EMAIL_TEMPLATE,
        'value'         => get_option( Option::RESOLVED_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Resolved', Plugin::ID ),
        'desc'          => __( 'Template Variables: subject, username, first_name, last_name, full_name, email', Plugin::ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_reply_email_template',
        'option'        => Option::REPLY_EMAIL_TEMPLATE,
        'value'         => get_option( Option::REPLY_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Reply To Ticket', Plugin::ID ),
        'desc'          => __( 'Template Variables: subject, agent, username, first_name, last_name, full_name, email, reply', Plugin::ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) );

$email_notifications = new SettingsSection( 'email_notifications', __( 'Notifications', Plugin::ID ) );

$email_notifications->add_field( new CheckBoxField(
    array(
        'id'            => 'support_notify_ticket_resolved',
        'option'        => Option::NOTIFY_RESOLVED,
        'value'         => get_option( Option::NOTIFY_RESOLVED, Option\Defaults::NOTIFY_RESOLVED ),
        'label'         => __( 'Ticket Resolved Notification', Plugin::ID ),
        'desc'          => __( 'Notify the user when their ticket is resolved', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$advanced = new SettingsSection( 'advanced', __( 'CAUTION: Some of these may bite', Plugin::ID ) );

$advanced->add_field( new CheckBoxField(
    array(
        'id'            => 'support_nuke_data',
        'option'        => Option::NUKE,
        'value'         => get_option( Option::NUKE, Option\Defaults::NUKE ),
        'label'         => __( 'Erase All Data', Plugin::ID ),
        'desc'          => __( 'Erase all data on plugin uninstall', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_enable_dev_mode',
        'option'        => Option::DEV_MODE,
        'value'         => get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ),
        'label'         => __( 'Developer Mode', Plugin::ID ),
        'desc'          => __( 'Enable Development functionality', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_restore_template',
        'option'        => Option::RESTORE_TEMPLATE,
        'value'         => '',
        'label'         => __( 'Restore Template Page', Plugin::ID ),
        'desc'          => __( 'Restore the template page if its been deleted', Plugin::ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$admin->add_section( $general, 'general' );
$admin->add_section( $email_notifications, 'notifications' );
$admin->add_section( $emails, 'notifications' );
$admin->add_section( $advanced, 'advanced' );
$admin->add_section( $text, 'display' );
$admin->add_section( $appearance, 'appearance' );

return $admin;
