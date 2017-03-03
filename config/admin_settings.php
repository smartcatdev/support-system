<?php

use smartcat\admin\CheckBoxField;
use smartcat\admin\IntegerValidator;
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
        'page_title'    => __( 'Support Settings', \SmartcatSupport\PLUGIN_ID ),
        'menu_title'    => __( 'Settings', \SmartcatSupport\PLUGIN_ID ),
        'menu_slug'     => 'support_options',
        'tabs'          => array(
            'general'       => __( 'General', \SmartcatSupport\PLUGIN_ID ),
            'display'       => __( 'Display', \SmartcatSupport\PLUGIN_ID ),
            'appearance'    => __( 'Appearance', \SmartcatSupport\PLUGIN_ID ),
            'notifications' => __( 'E-Mail', \SmartcatSupport\PLUGIN_ID ),
            'advanced'      => __( 'Advanced', \SmartcatSupport\PLUGIN_ID )
        )
    )
);

$appearance = new SettingsSection( 'appearance', __( 'Appearance', \SmartcatSupport\PLUGIN_ID ) );

$appearance->add_field( new TextField(
    array(
        'id'            => 'support_primary_color',
        'option'        => Option::PRIMARY_COLOR,
        'value'         => get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ),
        'label'         => __( 'Primary color', \SmartcatSupport\PLUGIN_ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_hover_color',
        'option'        => Option::HOVER_COLOR,
        'value'         => get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ),
        'label'         => __( 'Hover color', \SmartcatSupport\PLUGIN_ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_secondary_color',
        'option'        => Option::SECONDARY_COLOR,
        'value'         => get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ),
        'label'         => __( 'Secondary color', \SmartcatSupport\PLUGIN_ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_tertiary_color',
        'option'        => Option::TERTIARY_COLOR,
        'value'         => get_option( Option::TERTIARY_COLOR, Option\Defaults::TERTIARY_COLOR ),
        'label'         => __( 'Tertiary color', \SmartcatSupport\PLUGIN_ID ),
        'class'         => array( 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_show_footer',
        'option'        => Option::SHOW_FOOTER,
        'value'         => get_option( Option::SHOW_FOOTER, Option\Defaults::SHOW_FOOTER ),
        'label'         => __( 'Display Footer', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Display the footer on template pages', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$text = new SettingsSection( 'text', __( 'Text & Labels', \SmartcatSupport\PLUGIN_ID ) );

$text->add_field( new TextField(
    array(
        'id'            => 'support_login_disclaimer',
        'option'        => Option::LOGIN_DISCLAIMER,
        'value'         => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label'         => __( 'Login Disclaimer', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_register_btn_text',
        'option'        => Option::REGISTER_BTN_TEXT,
        'value'         => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label'         => __( 'Register Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_btn_text',
        'option'        => Option::LOGIN_BTN_TEXT,
        'value'         => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label'         => __( 'Login Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_create_btn_text',
        'option'        => Option::CREATE_BTN_TEXT,
        'value'         => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label'         => __( 'Create Ticket Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_cancel_btn_text',
        'option'        => Option::CANCEL_BTN_TEXT,
        'value'         => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label'         => __( 'Cancel Operation Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_created_msg',
        'option'        => Option::TICKET_CREATED_MSG,
        'value'         => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label'         => __( 'Ticket Created Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_updated_msg',
        'option'        => Option::TICKET_UPDATED_MSG,
        'value'         => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label'         => __( 'Ticket Updated Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_empty_table_msg',
        'option'        => Option::EMPTY_TABLE_MSG,
        'value'         => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label'         => __( 'No Tickets Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_comments_closed_msg',
        'option'        => Option::COMMENTS_CLOSED_MSG,
        'value'         => get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ),
        'label'         => __( 'Comments Closed Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_footer_text',
        'option'        => Option::FOOTER_TEXT,
        'value'         => get_option( Option::FOOTER_TEXT, Option\Defaults::FOOTER_TEXT ),
        'label'         => __( 'Footer Text', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) );

$general = new SettingsSection( 'general', __( 'General Settings', \SmartcatSupport\PLUGIN_ID ) );

$general->add_field( new TextField(
    array(
        'id'            => 'support_login_logo',
        'option'        => Option::LOGIN_LOGO,
        'value'         => get_option( Option::LOGIN_LOGO, Option\Defaults::LOGIN_LOGO ),
        'label'         => __( 'Login Logo Image', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_allow_signups',
        'option'        => Option::ALLOW_SIGNUPS,
        'value'         => get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ),
        'label'         => __( 'Allow users to signup', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Allow users to create accounts for submitting tickets', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_ecommerce_integration',
        'option'        => Option::ECOMMERCE_INTEGRATION,
        'value'         => get_option( Option::ECOMMERCE_INTEGRATION, Option\Defaults::ECOMMERCE_INTEGRATION ),
        'label'         => __( 'E-Commerce Integration', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Enable integration with Easy Digital Downloads or WooCommerce', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_empty_table_msg',
        'type'          => 'number',
        'option'        => Option::MAX_TICKETS,
        'value'         => get_option( Option::MAX_TICKETS, Option\Defaults::MAX_TICKETS ),
        'label'         => __( 'Tickets Per Page', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'The maximum number of tickets to be loaded per page', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )
) );

$emails = new SettingsSection( 'email_templates', __( 'Email Templates', \SmartcatSupport\PLUGIN_ID ) );

$email_templates = array( '' => __( 'Select an email template', \SmartcatSupport\PLUGIN_ID ) ) + Mailer::list_templates();

$emails->add_field( new SelectBoxField(
    array(
        'id'            => 'support_welcome_email_template',
        'option'        => Option::WELCOME_EMAIL_TEMPLATE,
        'value'         => get_option( Option::WELCOME_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Welcome', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Template Variables: username, password, first_name, last_name, full_name, email', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )
) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_resolved_email_template',
        'option'        => Option::RESOLVED_EMAIL_TEMPLATE,
        'value'         => get_option( Option::RESOLVED_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Resolved', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Template Variables: subject, username, first_name, last_name, full_name, email', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_reply_email_template',
        'option'        => Option::REPLY_EMAIL_TEMPLATE,
        'value'         => get_option( Option::REPLY_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Reply To Ticket', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Template Variables: subject, agent, username, first_name, last_name, full_name, email, reply', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) );

$email_notifications = new SettingsSection( 'email_notifications', __( 'E-Mail Notifications', \SmartcatSupport\PLUGIN_ID ) );

$email_notifications->add_field( new CheckBoxField(
    array(
        'id'            => 'support_email_notifications',
        'option'        => Option::EMAIL_NOTIFICATIONS,
        'value'         => get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ),
        'label'         => __( 'Email Notifications', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Send out automated email notifications in response to ticket events', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$advanced = new SettingsSection( 'advanced', __( 'CAUTION: Some of these may bite', \SmartcatSupport\PLUGIN_ID ) );

$advanced->add_field( new CheckBoxField(
    array(
        'id'            => 'support_nuke_data',
        'option'        => Option::NUKE,
        'value'         => get_option( Option::NUKE, Option\Defaults::NUKE ),
        'label'         => __( 'Erase All Data', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Erase all data on plugin uninstall', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_enable_dev_mode',
        'option'        => Option::DEV_MODE,
        'value'         => get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ),
        'label'         => __( 'Developer Mode', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Enable Development functionality', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_restore_template',
        'option'        => Option::RESTORE_TEMPLATE,
        'value'         => '',
        'label'         => __( 'Restore Template Page', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Restore the template page if its been deleted', \SmartcatSupport\PLUGIN_ID ),
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
