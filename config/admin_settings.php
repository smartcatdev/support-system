<?php

use smartcat\admin\CheckBoxField;
use smartcat\admin\HTMLFilter;
use smartcat\admin\IntegerValidator;
use smartcat\admin\MatchFilter;
use smartcat\admin\RangeValidator;
use smartcat\admin\SelectBoxField;
use smartcat\admin\SettingsSection;
use smartcat\admin\SettingsTab;
use smartcat\admin\TabbedMenuPage;
use smartcat\admin\TextAreaField;
use smartcat\admin\TextField;
use smartcat\admin\TextFilter;
use smartcat\mail\Mailer;
use ucare\descriptor\Option;
use ucare\Plugin;

$plugin_url = Plugin::plugin_url( \ucare\PLUGIN_ID );

$appearance = new SettingsSection( 'uc_appearance', __( 'Appearance', \ucare\PLUGIN_ID ) );

$appearance->add_field( new TextField(
    array(
        'id'            => 'support_primary_color',
        'option'        => Option::PRIMARY_COLOR,
        'class'         => array( 'regular-text', 'color_picker' ),
        'value'         => get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ),
        'label'         => __( 'Primary color', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_hover_color',
        'option'        => Option::HOVER_COLOR,
        'class'         => array( 'regular-text', 'color_picker' ),
        'value'         => get_option( Option::HOVER_COLOR, Option\Defaults::HOVER_COLOR ),
        'label'         => __( 'Hover color', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_secondary_color',
        'option'        => Option::SECONDARY_COLOR,
        'value'         => get_option( Option::SECONDARY_COLOR, Option\Defaults::SECONDARY_COLOR ),
        'label'         => __( 'Secondary color', \ucare\PLUGIN_ID ),
        'class'         => array( 'regular-text', 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_tertiary_color',
        'option'        => Option::TERTIARY_COLOR,
        'value'         => get_option( Option::TERTIARY_COLOR, Option\Defaults::TERTIARY_COLOR ),
        'label'         => __( 'Tertiary color', \ucare\PLUGIN_ID ),
        'class'         => array( 'regular-text', 'color_picker' ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_background_image',
        'class'         => array( 'image-upload' ),
        'option'        => Option::LOGIN_BACKGROUND,
        'value'         => get_option( Option::LOGIN_BACKGROUND, Option\Defaults::LOGIN_BACKGROUND ),
        'label'         => __( 'Login Background Image', \ucare\PLUGIN_ID )
    )

) );

$text = new SettingsSection( 'uc_text', __( 'Text & Labels', \ucare\PLUGIN_ID ) );

$text->add_field( new TextField(
    array(
        'id'            => 'support_login_disclaimer',
        'option'        => Option::LOGIN_DISCLAIMER,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::LOGIN_DISCLAIMER, Option\Defaults::LOGIN_DISCLAIMER ),
        'label'         => __( 'Login Disclaimer', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_register_btn_text',
        'option'        => Option::REGISTER_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::REGISTER_BTN_TEXT, Option\Defaults::REGISTER_BTN_TEXT ),
        'label'         => __( 'Register Button Label', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_login_btn_text',
        'option'        => Option::LOGIN_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::LOGIN_BTN_TEXT, Option\Defaults::LOGIN_BTN_TEXT ),
        'label'         => __( 'Login Button Label', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_create_btn_text',
        'option'        => Option::CREATE_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ),
        'label'         => __( 'Create Ticket Button Label', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_cancel_btn_text',
        'option'        => Option::CANCEL_BTN_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ),
        'label'         => __( 'Cancel Operation Button Label', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_created_msg',
        'option'        => Option::TICKET_CREATED_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TICKET_CREATED_MSG, Option\Defaults::TICKET_CREATED_MSG ),
        'label'         => __( 'Ticket Created Message', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_ticket_updated_msg',
        'option'        => Option::TICKET_UPDATED_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TICKET_UPDATED_MSG, Option\Defaults::TICKET_UPDATED_MSG ),
        'label'         => __( 'Ticket Updated Message', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_empty_table_msg',
        'option'        => Option::EMPTY_TABLE_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ),
        'label'         => __( 'No Tickets Message', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_comments_closed_msg',
        'option'        => Option::COMMENTS_CLOSED_MSG,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ),
        'label'         => __( 'Comments Closed Message', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_footer_text',
        'option'        => Option::FOOTER_TEXT,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::FOOTER_TEXT, Option\Defaults::FOOTER_TEXT ),
        'label'         => __( 'Footer Text', \ucare\PLUGIN_ID ),
        'validators'    => array( new TextFilter() )
    )
) );

$widgets = new SettingsSection( 'uc_widgets', __( 'Widgets', \ucare\PLUGIN_ID ) );

$widgets->add_field( new TextAreaField(
    array(
        'id'            => 'support_login_widget_area',
        'option'        => Option::LOGIN_WIDGET_AREA,
        'class'         => array( 'regular-text' ),
        'props'         => array( 'rows' => array( 5 ) ),
        'value'         => stripcslashes( get_option( Option::LOGIN_WIDGET_AREA, Option\Defaults::LOGIN_WIDGET_AREA ) ),
        'label'         => __( 'Login Widget Area', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Displayed on the login page', \ucare\PLUGIN_ID ),
        'validators'    => array( new HTMLFilter() )
    )
) )->add_field( new TextAreaField(
    array(
        'id'            => 'support_user_widget_area',
        'option'        => Option::USER_WIDGET_AREA,
        'class'         => array( 'regular-text' ),
        'props'         => array( 'rows' => array( 5 ) ),
        'value'         => stripcslashes( get_option( Option::USER_WIDGET_AREA, Option\Defaults::USER_WIDGET_AREA ) ),
        'label'         => __( 'User Widget Area', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Only visible to support users', \ucare\PLUGIN_ID ),
        'validators'    => array( new HTMLFilter() )
    )
) )->add_field( new TextAreaField(
    array(
        'id'            => 'support_agent_widget_area',
        'option'        => Option::AGENT_WIDGET_AREA,
        'class'         => array( 'regular-text' ),
        'props'         => array( 'rows' => array( 5 ) ),
        'value'         => stripcslashes( get_option( Option::AGENT_WIDGET_AREA, Option\Defaults::AGENT_WIDGET_AREA ) ),
        'label'         => __( 'Agent Widget Area', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Only visible to support agents and admins', \ucare\PLUGIN_ID ),
        'validators'    => array( new HTMLFilter() )
    )
) );

$general = new SettingsSection( 'uc_general', __( 'General Settings', \ucare\PLUGIN_ID ) );

$general->add_field( new TextField(
    array(
        'id'            => 'support_logo_image',
        'class'         => array( 'image-upload' ),
        'option'        => Option::LOGO,
        'value'         => get_option( Option::LOGO, $plugin_url . 'assets/images/logo.png' ),
        'label'         => __( 'Logo Image', \ucare\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_favicon',
        'class'         => array( 'image-upload' ),
        'option'        => Option::FAVICON,
        'value'         => get_option( Option::FAVICON, $plugin_url . 'assets/images/favicon.png' ),
        'label'         => __( 'Favicon', \ucare\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_company_name',
        'option'        => Option::COMPANY_NAME,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::COMPANY_NAME, Option\Defaults::COMPANY_NAME ),
        'label'         => __( 'Company Name', \ucare\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_terms_url',
        'type'          => 'url',
        'option'        => Option::TERMS_URL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TERMS_URL, home_url() ),
        'label'         => __( 'Terms & Conditions URL', \ucare\PLUGIN_ID ),
        'desc'          => __( 'URL of page containing your terms and conditions', \ucare\PLUGIN_ID )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_allow_signups',
        'option'        => Option::ALLOW_SIGNUPS,
        'value'         => get_option( Option::ALLOW_SIGNUPS, Option\Defaults::ALLOW_SIGNUPS ),
        'label'         => __( 'Allow users to signup', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Allow users to create accounts for submitting tickets', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_ecommerce_integration',
        'option'        => Option::ECOMMERCE,
        'value'         => get_option( Option::ECOMMERCE, Option\Defaults::ECOMMERCE ),
        'label'         => __( 'E-Commerce Integration', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Enable integration with Easy Digital Downloads or WooCommerce', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_tickets_per_page',
        'type'          => 'number',
        'option'        => Option::MAX_TICKETS,
        'value'         => get_option( Option::MAX_TICKETS, Option\Defaults::MAX_TICKETS ),
        'label'         => __( 'Tickets Per Page', \ucare\PLUGIN_ID ),
        'desc'          => __( 'The maximum number of tickets to be loaded per page', \ucare\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )
) )->add_field( new TextField(
    array(
        'id'            => 'support_max_attachment_size',
        'type'          => 'number',
        'option'        => Option::MAX_ATTACHMENT_SIZE,
        'value'         => get_option( Option::MAX_ATTACHMENT_SIZE, Option\Defaults::MAX_ATTACHMENT_SIZE ),
        'label'         => __( 'Maximum attachment size', \ucare\PLUGIN_ID ),
        'desc'          => __( 'The maximum file size for attachments in MB', \ucare\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_refresh_time',
        'type'          => 'number',
        'option'        => Option::REFRESH_INTERVAL,
        'value'         => get_option( Option::REFRESH_INTERVAL, Option\Defaults::REFRESH_INTERVAL ),
        'label'         => __( 'List Refresh Interval', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Automatic refresh interval in seconds', \ucare\PLUGIN_ID ),
        'validators'    => array( new IntegerValidator() )
    )

) );

$auto_close = new SettingsSection( 'uc_auto_close', __( 'Inactive Tickets', \ucare\PLUGIN_ID ) );

$auto_close_interval = get_option( Option::INACTIVE_MAX_AGE, Option\Defaults::INACTIVE_MAX_AGE );

$auto_close->add_field( new TextField(
    array(
        'id'            => 'support_autoclose_max-age',
        'type'          => 'number',
        'option'        => Option::INACTIVE_MAX_AGE,
        'value'         => $auto_close_interval,
        'label'         => __( 'Max Ticket Age', \ucare\PLUGIN_ID ),
        'desc'          => __( 'The maximum number of days of inactivity for a ticket', \ucare\PLUGIN_ID ),
        'props'         => array( 'max' => array( 356 ),'min' => array( 1 ) ),
        'validators'    => array( new IntegerValidator(), new RangeValidator( 1, 365, $auto_close_interval ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_autoclose_enabled',
        'option'        => Option::AUTO_CLOSE,
        'value'         => get_option( Option::AUTO_CLOSE, Option\Defaults::AUTO_CLOSE ),
        'label'         => __( 'Auto Close Tickets', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Automatically close tickets after they become inactive', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$emails = new SettingsSection( 'uc_email_templates', __( 'Email Templates', \ucare\PLUGIN_ID ) );

$email_templates = array( '' => __( 'Notifications Disabled', \ucare\PLUGIN_ID ) ) + Mailer::list_templates();

$emails->add_field( new SelectBoxField(
    array(
        'id'            => 'support_welcome_email_template',
        'option'        => Option::WELCOME_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::WELCOME_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Welcome', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Sent when a user registers for the first time', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_ticket_closed_email_template',
        'option'        => Option::TICKET_CLOSED_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::TICKET_CLOSED_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Closed', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Sent when the ticket is marked as closed', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_ticket_created_email_template',
        'option'        => Option::CREATED_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::CREATED_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Created', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Sent when a user creates a new ticket', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_reply_email_template',
        'option'        => Option::REPLY_EMAIL_TEMPLATE,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::REPLY_EMAIL_TEMPLATE ),
        'options'       => $email_templates,
        'label'         => __( 'Ticket Reply', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Sent when an agent replies to a ticket', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_pw_reset_email_template',
        'option'        => Option::PASSWORD_RESET_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::PASSWORD_RESET_EMAIL ),
        'options'       => $email_templates,
        'label'         => __( 'Forgot Password Reset', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Sent when a user forgets their password', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'support_autoclose_email_template',
        'option'        => Option::INACTIVE_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::INACTIVE_EMAIL ),
        'options'       => $email_templates,
        'label'         => __( 'Automatic Close Warning', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Notification sent out to warn users of automatic ticket closure', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array_keys( $email_templates ), '' ) )
    )

) );

$email_notifications = new SettingsSection( 'uc_email_notifications', __( 'Email Notifications', \ucare\PLUGIN_ID ) );

$email_notifications->add_field( new CheckBoxField(
    array(
        'id'            => 'support_email_notifications',
        'option'        => Option::EMAIL_NOTIFICATIONS,
        'value'         => get_option( Option::EMAIL_NOTIFICATIONS, Option\Defaults::EMAIL_NOTIFICATIONS ),
        'label'         => __( 'Email Notifications', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Send out automated email notifications in response to ticket events', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_forward_notification_email',
        'option'        => Option::FORWARD_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::FORWARD_EMAIL, get_option( 'admin_email' ) ),
        'label'         => __( 'Forward Email Address', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Comma separated list of email addresses that all notifications will be forwarded to', \ucare\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_email_sender_email',
        'option'        => Option::SENDER_EMAIL,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::SENDER_EMAIL, get_option( 'admin_email' ) ),
        'label'         => __( 'Sender Email', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Email address used for support emails', \ucare\PLUGIN_ID )
    )

) )->add_field( new TextField(
    array(
        'id'            => 'support_email_sender_name',
        'option'        => Option::SENDER_NAME,
        'class'         => array( 'regular-text' ),
        'value'         => get_option( Option::SENDER_NAME, __( 'uCare Support', \ucare\PLUGIN_ID ) ),
        'label'         => __( 'Sender Name', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Name used for support emails', \ucare\PLUGIN_ID )
    )

) );

$advanced = new SettingsSection( 'uc_advanced', __( 'CAUTION: Some of these may bite', \ucare\PLUGIN_ID ) );

$advanced->add_field( new CheckBoxField(
    array(
        'id'            => 'support_nuke_data',
        'option'        => Option::NUKE,
        'value'         => get_option( Option::NUKE, Option\Defaults::NUKE ),
        'label'         => __( 'Erase All Data', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Erase all data on plugin uninstall', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_enable_dev_mode',
        'option'        => Option::DEV_MODE,
        'value'         => get_option( Option::DEV_MODE, Option\Defaults::DEV_MODE ),
        'label'         => __( 'Developer Mode', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Enable Development functionality', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) )->add_field( new CheckBoxField(
    array(
        'id'            => 'support_restore_template',
        'option'        => Option::RESTORE_TEMPLATE,
        'value'         => '',
        'label'         => __( 'Restore Template Page', \ucare\PLUGIN_ID ),
        'desc'          => __( 'Restore the template page if its been deleted', \ucare\PLUGIN_ID ),
        'validators'    => array( new MatchFilter( array( '', 'on' ), '' ) )
    )

) );

$admin = new TabbedMenuPage(
    array(
        'type'          => 'submenu',
        'parent_menu'   => 'ucare_support',
        'menu_title'    => __( 'Settings', \ucare\PLUGIN_ID ),
        'menu_slug'     => 'support_options',
        'tabs'          => array(
            new SettingsTab(
                array(
                    'slug'     => 'uc_general',
                    'title'    => __( 'General', \ucare\PLUGIN_ID ),
                    'sections' => array( $general, $auto_close )
                )
            ),
            new SettingsTab(
                array(
                    'slug'     => 'uc_display',
                    'title'    => __( 'Display', \ucare\PLUGIN_ID ),
                    'sections' => array( $text, $widgets )
                )
            ),
            new SettingsTab(
                array(
                    'slug'     => 'uc_appearance',
                    'title'    => __( 'Appearance', \ucare\PLUGIN_ID ),
                    'sections' => array( $appearance )
                )
            ),
            new SettingsTab(
                array(
                    'slug'     => 'uc_email',
                    'title'    => __( 'Email', \ucare\PLUGIN_ID ),
                    'sections' => array( $emails, $email_notifications )
                )
            ),
            new SettingsTab(
                array(
                    'slug'     => 'uc_advanced',
                    'title'    => __( 'Advanced', \ucare\PLUGIN_ID ),
                    'sections' => array( $advanced )
                )
            )
        )
    )
);

return $admin;
