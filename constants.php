<?php

namespace ucare;

const PLUGIN_ID = 'smartcat_support';
const PLUGIN_VERSION = '1.4.0';

const MIN_PHP_VERSION = '5.5';


final class Options {

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
    const AGENT_REPLY_EMAIL = 'smartcat_support_reply-template';

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
    const TICKET_CREATED_EMAIL = 'smartcat_support_ticket-created-email';

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
    const TICKET_ASSIGNED = 'smartcat_support_ticket-assigned';

    /**
     * @since 1.3.0
     */
    const CUSTOMER_REPLY_EMAIL = 'smartcat_support_customer-reply';


    /**
     * @since 1.2.1
     */
    const LOGGING_ENABLED = 'smartcat_support_logging-enabled';

    /**
     * @since 1.3.0
     */
    const EXTENSION_LICENSE_NOTICES = 'smartcat_support_extension-license-notifications';

    /**
     * @since 1.3.0
     */
    const CATEGORIES_ENABLED = 'smartcat_support_enable-categories';

    /**
     * @since 1.3.0
     */
    const CATEGORIES_NAME = 'smartcat_support_extension-categories-name';

    /**
     * @since 1.3.0
     */
    const CATEGORIES_NAME_PLURAL = 'smartcat_support_extension-categories-name-plural';

    /**
     * @since 1.3.0
     */
    const ADMIN_EMAIL = 'smartcat_support_admin-email';

    /**
     * @since 1.3.0
     */
    const NEW_TICKET_ADMIN_EMAIL = 'smartcat_support_new-ticket-admin-email';

    /**
     * @since 1.3.0
     */
    const FIRST_RUN = 'smartcat_support_first-run';

    /**
     * @since 1.4.0
     */
    const FIRST_140_RUN = 'smartcat_support_first-140-run';

    /**
     * @since 1.3.0
     */
    const PRIMARY_FONT = 'smartcat_support_primary-font';

    /**
     * @since 1.3.0
     */
    const SECONDARY_FONT = 'smartcat_support_secondary-font';

    /**
     * @since 1.3.0
     */
    const QUICK_LINK_ENABLED = 'smartcat_support_quick-link-enabled';

    /**
     * @since 1.3.0
     */
    const QUICK_LINK_LABEL = 'smartcat_support_quick-link-label';

    /**
     * @since 1.4.0
     */
    const DISPLAY_BACK_BUTTON = 'smartcat_support_display_back_button';
}


final class Defaults {

    /**
     * @since 1.0.0
     */
    const NUKE = '';

    /**
     * @since 1.0.0
     */
    const DEV_MODE = '';

    /**
     * @since 1.0.0
     */
    const TICKET_CREATED_MSG = "We've received your request for support and an agent will get back to you soon";

    /**
     * @since 1.0.0
     */
    const TICKET_UPDATED_MSG = 'This ticket has been updated';

    /**
     * @since 1.0.0
     */
    const EMPTY_TABLE_MSG = 'There are no tickets yet';

    /**
     * @since 1.0.0
     */
    const COMMENTS_CLOSED_MSG = 'This ticket has been marked as closed and comments have been disabled';

    /**
     * @since 1.0.0
     */
    const CREATE_BTN_TEXT = 'Create Ticket';

    /**
     * @since 1.0.0
     */
    const REPLY_BTN_TEXT = 'Reply';

    /**
     * @since 1.0.0
     */
    const CANCEL_BTN_TEXT = 'Cancel';

    /**
     * @since 1.0.0
     */
    const SAVE_BTN_TEXT = 'Save';

    /**
     * @since 1.0.0
     */
    const REGISTER_BTN_TEXT = 'Register';

    /**
     * @since 1.0.0
     */
    const LOGIN_BTN_TEXT = 'Back To Login';

    /**
     * @since 1.0.0
     */
    const TEMPLATE_PAGE_ID = -1;

    /**
     * @since 1.0.0
     */
    const ECOMMERCE = 'on';

    /**
     * @since 1.0.0
     */
    const EMAIL_NOTIFICATIONS = 'on';

    /**
     * @since 1.0.0
     */
    const ALLOW_SIGNUPS = 'on';

    /**
     * @since 1.0.0
     */
    const LOGIN_DISCLAIMER = 'By registering, you agree to the terms and conditions';

    /**
     * @since 1.0.0
     */
    const LOGO = 'http://ps.w.org/our-team-enhanced/assets/icon-256x256.png';

    /**
     * @since 1.0.0
     */
    const PRIMARY_COLOR = '#188976';

    /**
     * @since 1.0.0
     */
    const SECONDARY_COLOR = '#273140';

    /**
     * @since 1.0.0
     */
    const TERTIARY_COLOR = '#30aabc';

    /**
     * @since 1.0.0
     */
    const HOVER_COLOR = '#1aaa9b';

    /**
     * @since 1.0.0
     */
    const MAX_TICKETS = 20;

    /**
     * @since 1.0.0
     */
    const FOOTER_TEXT = 'Copyright © 2017';

    /**
     * @since 1.0.2
     */
    const MAX_ATTACHMENT_SIZE = 2;

    /**
     * @since 1.0.2
     */
    const COMPANY_NAME = '';

    /**
     * @since 1.0.2
     */
    const FORWARD_EMAIL = '';

    /**
     * @since 1.0.2
     */
    const LOGIN_BACKGROUND = 'https://cloud.githubusercontent.com/assets/3696057/24772412/3b2e2412-1adf-11e7-85fa-c0acc52c59a0.jpg';

    /**
     * @since 1.0.2
     */
    const LOGIN_WIDGET_AREA = '';

    /**
     * @since 1.0.2
     */
    const USER_WIDGET_AREA = '';

    /**
     * @since 1.0.2
     */
    const AGENT_WIDGET_AREA = '';

    /**
     * @since 1.0.2
     */
    const SENDER_NAME = 'uCare Support';

    /**
     * @since 1.0.2
     */
    const SENDER_EMAIL = '';

    /**
     * @since 1.0.2
     */
    const TERMS_URL = '#';

    /**
     * @since 1.1
     */
    const REFRESH_INTERVAL = '60';


    /**
     * @since 1.2.0
     */
    const INACTIVE_MAX_AGE = 4;

    /**
     * @since 1.2.0
     */
    const AUTO_CLOSE = '';

    /**
     * @since 1.2.1
     */
    const LOGGING_ENABLED = 'on';

    /**
     * @since 1.3.0
     */
    const CATEGORIES_ENABLED = 'on';

    /**
     * @since 1.3.0
     */
    const CATEGORIES_NAME = 'category';

    /**
     * @since 1.3.0
     */
    const CATEGORIES_NAME_PLURAL = 'categories';

    /**
     * @since 1.3.0
     */
    const PRIMARY_FONT = 'Montserrat, sans-serif';

    /**
     * @since 1.3.0
     */
    const SECONDARY_FONT = 'Montserrat, sans-serif';

    /**
     * @since 1.3.0
     */
    const QUICK_LINK_ENABLED = 'on';

    /**
     * @since 1.3.0
     */
    const QUICK_LINK_LABEL = 'Get Support';

    /**
     * @since 1.4.0
     */
    const DISPLAY_BACK_BUTTON = 'on';

}
