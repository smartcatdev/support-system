<?php

use smartcat\admin\CheckBoxField;
use smartcat\admin\HTMLFilter;
use smartcat\admin\IntegerValidator;
use smartcat\admin\MatchFilter;
use smartcat\admin\SelectBoxField;
use smartcat\admin\SettingsSection;
use smartcat\admin\TabbedSettingsPage;
use smartcat\admin\TextAreaField;
use smartcat\admin\TextField;
use smartcat\admin\TextFilter;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

$plugin_url = Plugin::plugin_url( \SmartcatSupport\PLUGIN_ID );

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
            'notifications' => __( 'Email', \SmartcatSupport\PLUGIN_ID ),
            'advanced'      => __( 'Advanced', \SmartcatSupport\PLUGIN_ID )
        )
    )
);

$appearance = new SettingsSection( 'appearance', __( 'Appearance', \SmartcatSupport\PLUGIN_ID ) );

$appearance->add_field( new TextField(
    array(
        'id'            => 'support_primary_color',
        'option'        => Option::PRIMARY_COLOR,
        'class'         => array( 'regular-text', 'color_picker' ),
        'value'         => get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ),
        'label'         => __( 'Primary color', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_hover_color',
        'option'        => Option::HOVER_COLOR,
        'class'         => array( 'regular-text', 'color_picker' ),
        'value'         => get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ),
        'label'         => __( 'Hover color', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_secondary_color',
        'option'        => Option::SECONDARY_COLOR,
        'value'         => get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ),
        'label'         => __( 'Secondary color', \SmartcatSupport\PLUGIN_ID ),
        'class'         => array( 'regular-text', 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_tertiary_color',
        'option'        => Option::TERTIARY_COLOR,
        'value'         => get_option( Option::TERTIARY_COLOR, Option\Defaults::TERTIARY_COLOR ),
        'label'         => __( 'Tertiary color', \SmartcatSupport\PLUGIN_ID ),
        'class'         => array( 'regular-text', 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_background_image',
        'class'         => array( 'image-upload' ),
        'option'        => Option::LOGIN_BACKGROUND,
        'value'         => get_option( Option::LOGIN_BACKGROUND, Option\Defaults::LOGIN_BACKGROUND ),
        'label'         => __( 'Login Background Image', \SmartcatSupport\PLUGIN_ID )
    )

) );

$text = new SettingsSection( 'text', __( 'Text & Labels', \SmartcatSupport\PLUGIN_ID ) );

$text->add_field( new TextField(
    array(
        'id'            => 'support_login_disclaimer',
        'option'        => Option::LOGIN_DISCLAIMER,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label'         => __( 'Login Disclaimer', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_register_btn_text',
        'option'        => Option::REGISTER_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label'         => __( 'Register Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_btn_text',
        'option'        => Option::LOGIN_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label'         => __( 'Login Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_create_btn_text',
        'option'        => Option::CREATE_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label'         => __( 'Create Ticket Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_cancel_btn_text',
        'option'        => Option::CANCEL_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label'         => __( 'Cancel Operation Button Label', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_created_msg',
        'option'        => Option::TICKET_CREATED_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label'         => __( 'Ticket Created Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_updated_msg',
        'option'        => Option::TICKET_UPDATED_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label'         => __( 'Ticket Updated Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_empty_table_msg',
        'option'        => Option::EMPTY_TABLE_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label'         => __( 'No Tickets Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_comments_closed_msg',
        'option'        => Option::COMMENTS_CLOSED_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ),
        'label'         => __( 'Comments Closed Message', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_footer_text',
        'option'        => Option::FOOTER_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::FOOTER_TEXT, Option\Defaults::FOOTER_TEXT ),
        'label'         => __( 'Footer Text', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) );

$widgets = new SettingsSection( 'widgets', __( 'Widgets', \SmartcatSupport\PLUGIN_ID ) );

$widgets->add_field( new TextAreaField(
    array(
        'id'            => 'support_login_widget_area',
        'option'        => Option::LOGIN_WIDGET_AREA,
        'class'         => array( 'regular-text' ),
        'props'         => array( 'rows' => array( 5 ) ),
        'value'         => stripcslashes( get_option( Option::LOGIN_WIDGET_AREA, Option\Defaults::LOGIN_WIDGET_AREA ) ),
        'label'         => __( 'Login Widget Area', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Displayed on the login page', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new HTMLFilter() )
    )
) )->add_field( new TextAreaField(
    array(
        'id'            => 'support_user_widget_area',
        'option'        => Option::USER_WIDGET_AREA,
        'class'         => array( 'regular-text' ),
        'props'         => array( 'rows' => array( 5 ) ),
        'value'         => stripcslashes( get_option( Option::USER_WIDGET_AREA, Option\Defaults::USER_WIDGET_AREA ) ),
        'label'         => __( 'User Widget Area', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Only visible to support users', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new HTMLFilter() )
    )
) )->add_field( new TextAreaField(
    array(
        'id'            => 'support_agent_widget_area',
        'option'        => Option::AGENT_WIDGET_AREA,
        'class'         => array( 'regular-text' ),
        'props'         => array( 'rows' => array( 5 ) ),
        'value'         => stripcslashes( get_option( Option::AGENT_WIDGET_AREA, Option\Defaults::AGENT_WIDGET_AREA ) ),
        'label'         => __( 'Agent Widget Area', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Only visible to support agents and admins', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new HTMLFilter() )
    )
) );

$general = new SettingsSection( 'general', __( 'General Settings', \SmartcatSupport\PLUGIN_ID ) );

$general->add_field( new TextField(
    array(
        'id'            => 'support_logo_image',
        'class'         => array( 'image-upload' ),
        'option'        => Option::LOGO,
        'value'         => get_option( Option::LOGO, $plugin_url . 'assets/images/logo.png' ),
        'label'         => __( 'Logo Image', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_favicon',
        'class'         => array( 'image-upload' ),
        'option'        => Option::FAVICON,
        'value'         => get_option( Option::FAVICON, $plugin_url . 'assets/images/favicon.png' ),
        'label'         => __( 'Favicon', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_company_name',
        'option'        => Option::COMPANY_NAME,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::COMPANY_NAME, Option\Defaults::COMPANY_NAME ),
        'label'         => __( 'Company Name', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_terms_url',
        'type'          => 'url',
        'option'        => Option::TERMS_URL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TERMS_URL, home_url() ),
        'label'         => __( 'Terms & Conditions URL', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'URL of page containing your terms and conditions', \SmartcatSupport\PLUGIN_ID )
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
        'option'        => Option::ECOMMERCE,
        'value'         => get_option( Option::ECOMMERCE, Option\Defaults::ECOMMERCE ),
        'label'         => __( 'E-Commerce Integration', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Enable integration with Easy Digital Downloads or WooCommerce', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_tickets_per_page',
        'type'          => 'number',
        'option'        => Option::MAX_TICKETS,
        'value'         => get_option( Option::MAX_TICKETS, Option\Defaults::MAX_TICKETS ),
        'label'         => __( 'Tickets Per Page', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'The maximum number of tickets to be loaded per page', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_max_attachment_size',
        'type'          => 'number',
        'option'        => Option::MAX_ATTACHMENT_SIZE,
        'value'         => get_option( Option::MAX_ATTACHMENT_SIZE, Option\Defaults::MAX_ATTACHMENT_SIZE ),
        'label'         => __( 'Maximum attachment size', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'The maximum file size for attachments in MB', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_refresh_time',
        'type'          => 'number',
        'option'        => Option::REFRESH_INTERVAL,
        'value'         => get_option( Option::REFRESH_INTERVAL, Option\Defaults::REFRESH_INTERVAL ),
        'label'         => __( 'Refresh Interval', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Automatic refresh interval in seconds', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )

) );

$emails = new SettingsSection( 'email_templates', __( 'Email Templates', \SmartcatSupport\PLUGIN_ID ) );

$email_templates = array( '' => __( 'Notifications Disabled', \SmartcatSupport\PLUGIN_ID ) ) + Mailer::list_templates();

$emails->add_field( new SelectBoxField(
    array(
        'id'            => 'support_welcome_email_template',
        'option'        => Option::WELCOME_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::WELCOME_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Welcome', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Sent when a user registers for the first time', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_ticket_closed_email_template',
        'option'        => Option::TICKET_CLOSED_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Closed', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Sent when the ticket is marked as closed', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_ticket_created_email_template',
        'option'        => Option::CREATED_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::CREATED_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Created', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Sent when a user creates a new ticket', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_reply_email_template',
        'option'        => Option::REPLY_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::REPLY_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Reply', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Sent when an agent replies to a ticket', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_pw_reset_email_template',
        'option'        => Option::PASSWORD_RESET_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::PASSWORD_RESET_EMAIL ),
        'options'       => $email_templates,
        'label'         => __( 'Forgot Password Reset', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Sent when a user forgets their password', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) );

$email_notifications = new SettingsSection( 'email_notifications', __( 'Email Notifications', \SmartcatSupport\PLUGIN_ID ) );

$email_notifications->add_field( new CheckBoxField(
    array(
        'id'            => 'support_email_notifications',
        'option'        => Option::EMAIL_NOTIFICATIONS,
        'value'         => get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ),
        'label'         => __( 'Email Notifications', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Send out automated email notifications in response to ticket events', \SmartcatSupport\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_forward_notification_email',
        'option'        => Option::FORWARD_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::FORWARD_EMAIL, get_option( 'admin_email' ) ),
        'label'         => __( 'Forward Email Address', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Comma separated list of email addresses that all notifications will be forwarded to', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_email_sender_email',
        'option'        => Option::SENDER_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::SENDER_EMAIL, get_option( 'admin_email' ) ),
        'label'         => __( 'Sender Email', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Email address used for support emails', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_email_sender_name',
        'option'        => Option::SENDER_NAME,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::SENDER_NAME, __( 'uCare Support', \SmartcatSupport\PLUGIN_ID ) ),
        'label'         => __( 'Sender Name', \SmartcatSupport\PLUGIN_ID ),
        'desc'          => __( 'Name used for support emails', \SmartcatSupport\PLUGIN_ID )
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
$admin->add_section( $widgets, 'display' );
$admin->add_section( $appearance, 'appearance' );

return $admin;
