<?php

namespace ucare;

/**
 * @since 1.0.0
 */
const PLUGIN_ID = 'smartcat_support';

/**
 * @since 1.0.0
 */
const PLUGIN_VERSION = '1.7.1';

/**
 * @since 1.0.0
 */
const MIN_PHP_VERSION = '5.5';


final class Marketing {

    /**
     * @since 1.6.0
     */
    const    ADMIN_NOTIFICATION         = 'admin-notification'
            ,SETTINGS_SIDEBAR_UCARE_PRO = 'settings-sidebar-ucare-pro'
            ,SETTINGS_SIDEBAR_FEEDBACK  = 'settings-sidebar-feedback'
    ;

}


final class Options {

            /**
             * @since 1.0.0
             */
    const    PLUGIN_VERSION                 = 'smartcat_support_version'
            ,NUKE                           = 'smartcat_support_erase'
            ,DEV_MODE                       = 'smartcat_support_dev-mode'
            ,EMAIL_NOTIFICATIONS            = 'smartcat_support_email-notifications'
            ,STATUSES                       = 'smartcat_support_statuses'
            ,PRIORITIES                     = 'smartcat_support_priorities'
            ,TICKET_CREATED_MSG             = 'smartcat_support_string_ticket-created'
            ,TICKET_UPDATED_MSG             = 'smartcat_support_string_ticket-updated'
            ,EMPTY_TABLE_MSG                = 'smartcat_support_string_empty-table'
            ,COMMENTS_CLOSED_MSG            = 'smartcat_support_string_comments-closed'
            ,CREATE_BTN_TEXT                = 'smartcat_support_string_create-ticket-btn'
            ,REPLY_BTN_TEXT                 = 'smartcat_support_string_reply-btn'
            ,CANCEL_BTN_TEXT                = 'smartcat_support_string_cancel-btn'
            ,SAVE_BTN_TEXT                  = 'smartcat_support_string_save-ticket-btn'
            ,REGISTER_BTN_TEXT              = 'smartcat_support_string_register-btn'
            ,LOGIN_BTN_TEXT                 = 'smartcat_support_string_login-btn'
            ,TEMPLATE_PAGE_ID               = 'smartcat_support_page-id'
            ,ECOMMERCE                      = 'smartcat_support_ecommerce-integration'
            ,ALLOW_SIGNUPS                  = 'smartcat_support_allow-signups'
            ,LOGIN_DISCLAIMER               = 'smartcat_support_login-disclaimer'
            ,LOGO                           = 'smartcat_support_login-logo'
            ,RESTORE_TEMPLATE               = 'smartcat_support_regenerate-template'
            ,WELCOME_EMAIL_TEMPLATE         = 'smartcat_support_welcome-email-template'
            ,TICKET_CLOSED_EMAIL_TEMPLATE   = 'smartcat_support_closed-email-template'
            ,AGENT_REPLY_EMAIL              = 'smartcat_support_reply-template'
            ,PRIMARY_COLOR                  = 'smartcat_support_primary-color'
            ,SECONDARY_COLOR                = 'smartcat_support_secondary-color'
            ,TERTIARY_COLOR                 = 'smartcat_support_tertiary-color'
            ,HOVER_COLOR                    = 'smartcat_support_hover-color'
            ,MAX_TICKETS                    = 'smartcat_support_max-tickets-per-page'
            ,FOOTER_TEXT                    = 'smartcat_support_footer-text'

            /**
             * @since 1.0.2
             */
            ,MAX_ATTACHMENT_SIZE            = 'smartcat_support_footer-max-attachment-size'
            ,COMPANY_NAME                   = 'smartcat_support_footer-company-name'
            ,TICKET_CREATED_EMAIL           = 'smartcat_support_ticket-created-email'
            ,LOGIN_BACKGROUND               = 'smartcat_support_login-background'
            ,LOGIN_WIDGET_AREA              = 'smartcat_support_login-widget-area'
            ,USER_WIDGET_AREA               = 'smartcat_support_user-widget-area'
            ,AGENT_WIDGET_AREA              = 'smartcat_support_agent-widget-area'
            ,SENDER_NAME                    = 'smartcat_support_sender-name'
            ,SENDER_EMAIL                   = 'smartcat_support_sender-email'
            ,TERMS_URL                      = 'smartcat_support_terms-url'

            /**
             * @since 1.1.0
             */
            ,REFRESH_INTERVAL               = 'smartcat_support_refresh-interval'
            ,FAVICON                        = 'smartcat_support_favicon'
            ,PASSWORD_RESET_EMAIL           = 'smartcat_support_password-reset-email'

            /**
             * @since 1.2.0
             */
            ,INACTIVE_MAX_AGE               = 'smartcat_support_inactive-max-age'
            ,AUTO_CLOSE                     = 'smartcat_support_autoclose-enabled'
            ,INACTIVE_EMAIL                 = 'smartcat_support_inactive-email'

            /**
             * @since 1.2.1
             */
            ,LOGGING_ENABLED                = 'smartcat_support_logging-enabled'

            /**
             * @since 1.3.0
             */
            ,TICKET_ASSIGNED                = 'smartcat_support_ticket-assigned'
            ,CUSTOMER_REPLY_EMAIL           = 'smartcat_support_customer-reply'
            ,EXTENSION_LICENSE_NOTICES      = 'smartcat_support_extension-license-notifications'
            ,CATEGORIES_ENABLED             = 'smartcat_support_enable-categories'
            ,CATEGORIES_NAME                = 'smartcat_support_extension-categories-name'
            ,CATEGORIES_NAME_PLURAL         = 'smartcat_support_extension-categories-name-plural'
            ,ADMIN_EMAIL                    = 'smartcat_support_admin-email'
            ,NEW_TICKET_ADMIN_EMAIL         = 'smartcat_support_new-ticket-admin-email'
            ,FIRST_RUN                      = 'smartcat_support_first-run'
            ,PRIMARY_FONT                   = 'smartcat_support_primary-font'
            ,SECONDARY_FONT                 = 'smartcat_support_secondary-font'
            ,QUICK_LINK_LABEL               = 'smartcat_support_quick-link-label'
            ,QUICK_LINK_ENABLED             = 'smartcat_support_quick-link-enabled'

            /**
             * @since 1.4.0
             */
            ,FIRST_140_RUN                  = 'smartcat_support_first-140-run'
            ,DISPLAY_BACK_BUTTON            = 'smartcat_support_display_back_button'

            /**
             * @since 1.4.1
             */
            ,IMAGE_MIME_TYPES               = 'smartcat_support_image_mime_types'
            ,FILE_MIME_TYPES                = 'smartcat_support_file_mime_types'

            /**
             * @since 1.5.1
             */
            ,CREATE_TICKET_PAGE_ID          = 'smartcat_support_create_ticket_page_id'

            /**
             * @since 1.6.0
             */
            ,EDIT_PROFILE_PAGE_ID           = 'smartcat_support_edit_profile_page_id'
            ,RECEIPT_ID_LABEL               = 'smartcat_support_receipt_id_label'
            ,LOGIN_PAGE_ID                  = 'smartcat_support_login_page_id'
            ,DATABASE_VERSION               = 'smartcat_support_db_version'
            ,FIRST_160_RUN                  = 'smartcat_support_first-160-run'

            /**
             * @since 1.7.0
             */
            ,LOGIN_TITLE                    = 'smartcat_support_login_title'
            ,LOGIN_SUBTEXT                  = 'smartcat_support_login_subtext'
            ,ENFORCE_TOS                    = 'smartcat_support_enforce_tos'
            ,TOS_TITLE                      = 'smartcat_support_tos_title'
            ,TOS_POLICY                     = 'smartcat_support_tos_policy'
            ,TOS_REVISION                   = 'smartcat_support_tos_revision'
            ,REGISTRATION_TITLE             = 'smartcat_support_registration_title'
            ,REGISTRATION_SUBTEXT           = 'smartcat_support_registration_subtext'
            ,ADMIN_REDIRECT                 = 'smartcat_support_admin_redirect'
            ,MIN_PW_LENGTH                  = 'smartcat_support_min_pw_length'

            /**
             * @since 1.7.1
             */
            ,ALLOW_SUBSCRIBERS              = 'smartcat_support_allow_subscribers'

            /**
             * @deprecated
             */
            ,SHOW_CLOCK                     = 'smartcat_support_show_clock'
    ;
}

/**
 * Class Defaults
 * @todo migrate defaults to register_settings
 * @package ucare
 */
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
    const FOOTER_TEXT = 'Copyright © 2018';

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

    /**
     * @since 1.4.1
     */
    const IMAGE_MIME_TYPES = 'image/jpg,image/jpeg,image/png,image/gif';

    /**
     * @since 1.4.1
     */
    const FILE_MIME_TYPES = 'application/pdf,application/zip,text/csv';

    /**
     * @since 1.4.1
     * @deprecated
     */
    const SHOW_CLOCK = 'on';

}
