<?php

namespace ucare\descriptor;

/**
 * Constant keys for use with calls to get_option()
 * 
 * @since 1.0.0
 * @package desc
 * @author Eric Green <eric@smartcat.ca>
 */
final class Option {
    
    /**
     * @since 1.0.0
     */
    const PLUGIN_VERSION = 'smartcat_support_version';
    
    /**
     * @since 1.0.0
     */
    const NUKE = 'smartcat_support_erase';

    /**
     * @since 1.0.0
     */
    const DEV_MODE = 'smartcat_support_dev-mode';

    /**
     * @since 1.0.0
     */
    const EMAIL_NOTIFICATIONS = 'smartcat_support_email-notifications';

    /**
     * @since 1.0.0
     */
    const STATUSES = 'smartcat_support_statuses';

    /**
     * @since 1.0.0
     */
    const PRIORITIES = 'smartcat_support_priorities';

    /**
     * @since 1.0.0
     */
    const TICKET_CREATED_MSG = 'smartcat_support_string_ticket-created';

    /**
     * @since 1.0.0
     */
    const TICKET_UPDATED_MSG = 'smartcat_support_string_ticket-updated';

    /**
     * @since 1.0.0
     */
    const EMPTY_TABLE_MSG = 'smartcat_support_string_empty-table';

    /**
     * @since 1.0.0
     */
    const COMMENTS_CLOSED_MSG = 'smartcat_support_string_comments-closed';

    /**
     * @since 1.0.0
     */
    const CREATE_BTN_TEXT = 'smartcat_support_string_create-ticket-btn';

    /**
     * @since 1.0.0
     */
    const REPLY_BTN_TEXT = 'smartcat_support_string_reply-btn';

    /**
     * @since 1.0.0
     */
    const CANCEL_BTN_TEXT = 'smartcat_support_string_cancel-btn';

    /**
     * @since 1.0.0
     */
    const SAVE_BTN_TEXT = 'smartcat_support_string_save-ticket-btn';

    /**
     * @since 1.0.0
     */
    const REGISTER_BTN_TEXT = 'smartcat_support_string_register-btn';

    /**
     * @since 1.0.0
     */
    const LOGIN_BTN_TEXT = 'smartcat_support_string_login-btn';

    /**
     * @since 1.0.0
     */
    const TEMPLATE_PAGE_ID = 'smartcat_support_page-id';

    /**
     * @since 1.0.0
     */
    const ECOMMERCE = 'smartcat_support_ecommerce-integration';

    /**
     * @since 1.0.0
     */
    const ALLOW_SIGNUPS = 'smartcat_support_allow-signups';
    
    /**
     * @since 1.0.0
     */
    const LOGIN_DISCLAIMER = 'smartcat_support_login-disclaimer';
    
    /**
     * @since 1.0.0
     */
    const LOGO = 'smartcat_support_login-logo';

    /**
     * @since 1.0.0
     */
    const RESTORE_TEMPLATE = 'smartcat_support_regenerate-template';

    /**
     * @since 1.0.0
     */
    const WELCOME_EMAIL_TEMPLATE = 'smartcat_support_welcome-email-template';

    /**
     * @since 1.0.0
     */
    const TICKET_CLOSED_EMAIL_TEMPLATE = 'smartcat_support_closed-email-template';

    /**
     * @since 1.0.0
     */
    const REPLY_EMAIL_TEMPLATE = 'smartcat_support_reply-template';

    /**
     * @since 1.0.0
     */
    const PRIMARY_COLOR = 'smartcat_support_primary-color';

    /**
     * @since 1.0.0
     */
    const SECONDARY_COLOR = 'smartcat_support_secondary-color';

    /**
     * @since 1.0.0
     */
    const TERTIARY_COLOR = 'smartcat_support_tertiary-color';

    /**
     * @since 1.0.0
     */
    const HOVER_COLOR = 'smartcat_support_hover-color';

    /**
     * @since 1.0.0
     */
    const MAX_TICKETS = 'smartcat_support_max-tickets-per-page';

    /**
     * @since 1.0.0
     */
    const FOOTER_TEXT = 'smartcat_support_footer-text';

    /**
     * @since 1.0.2
     */
    const MAX_ATTACHMENT_SIZE = 'smartcat_support_footer-max-attachment-size';

    /**
     * @since 1.0.2
     */
    const COMPANY_NAME = 'smartcat_support_footer-company-name';

    /**
     * @since 1.0.2
     */
    const CREATED_EMAIL_TEMPLATE = 'smartcat_support_ticket-created-email';

    /**
     * @since 1.0.2
     */
    const FORWARD_EMAIL = 'smartcat_support_ticket-forward-address';

    /**
     * @since 1.0.2
     */
    const LOGIN_BACKGROUND = 'smartcat_support_login-background';

    /**
     * @since 1.0.2
     */
    const LOGIN_WIDGET_AREA = 'smartcat_support_login-widget-area';

    /**
     * @since 1.0.2
     */
    const USER_WIDGET_AREA = 'smartcat_support_user-widget-area';

    /**
     * @since 1.0.2
     */
    const AGENT_WIDGET_AREA = 'smartcat_support_agent-widget-area';

    /**
     * @since 1.0.2
     */
    const SENDER_NAME = 'smartcat_support_sender-name';

    /**
     * @since 1.0.2
     */
    const SENDER_EMAIL = 'smartcat_support_sender-email';

    /**
     * @since 1.0.2
     */
    const TERMS_URL = 'smartcat_support_terms-url';

    /**
     * @since 1.1
     */
    const REFRESH_INTERVAL = 'smartcat_support_refresh-interval';

    /**
     * @since 1.1
     */
    const FAVICON = 'smartcat_support_favicon';

    /**
     * @since 1.1.1
     */
    const PASSWORD_RESET_EMAIL = 'smartcat_support_password-reset-email';

    /**
     * @since 1.2.0
     */
    const INACTIVE_MAX_AGE = 'smartcat_support_inactive-max-age';

    /**
     * @since 1.2.0
     */
    const AUTO_CLOSE = 'smartcat_support_autoclose-enabled';

    /**
     * @since 1.2.0
     */
    const INACTIVE_EMAIL = 'smartcat_support_inactive-email';

    /**
     * @since 1.3.0
     */
    const AGENT_NOTIFICATION_EMAIL = 'smartcat_support_agent-notifications';
}
