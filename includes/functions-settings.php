<?php
/**
 * Settings registrations and other settings API related functions.
 *
 * @package ucare
 * @since 1.4.2
 */
namespace ucare;


// Register settings
add_action( 'init', 'ucare\register_settings' );


/**
 * Action to register settings with the settings API.
 *
 * @action init
 *
 * @since 1.4.2
 * @return void
 */
function register_settings() {
    /**
     *
     * @since 1.0.0
     */
    register_setting( 'uc-advanced', Options::TEMPLATE_PAGE_ID, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id',
    ) );

    register_setting( 'uc-appearance', Options::PRIMARY_FONT, array(
        'type'    => 'string',
        'default' => Defaults::PRIMARY_FONT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-appearance', Options::SECONDARY_FONT, array(
        'type'    => 'string',
        'default' => Defaults::SECONDARY_FONT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-appearance', Options::PRIMARY_COLOR, array(
        'type'    => 'string',
        'default' => Defaults::PRIMARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color'
    ) );

    register_setting( 'uc-appearance', Options::SECONDARY_COLOR, array(
        'type'    => 'string',
        'default' => Defaults::SECONDARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color'
    ) );

    register_setting( 'uc-appearance', Options::TERTIARY_COLOR, array(
        'type'    => 'string',
        'default' => Defaults::TERTIARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color'
    ) );

    register_setting( 'uc-appearance', Options::SECONDARY_COLOR, array(
        'type'    => 'string',
        'default' => Defaults::SECONDARY_COLOR,
        'sanitize_callback' => 'sanitize_hex_color'
    ) );

    register_setting( 'uc-appearance', Options::LOGIN_BACKGROUND, array(
        'type'    => 'string',
        'default' => resolve_url( 'assets/images/login-background.jpg' ),
        'sanitize_callback' => 'esc_url_raw'
    ) );

    register_setting( 'uc-appearance', Options::DISPLAY_BACK_BUTTON, array(
        'type'    => 'string',
        'default' => Defaults::DISPLAY_BACK_BUTTON,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-display', Options::CATEGORIES_NAME, array(
        'type'    => 'string',
        'default' => Defaults::CATEGORIES_NAME,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::CATEGORIES_NAME_PLURAL, array(
        'type'    => 'string',
        'default' => Defaults::CATEGORIES_NAME_PLURAL,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::CATEGORIES_ENABLED, array(
        'type'    => 'string',
        'default' => Defaults::CATEGORIES_ENABLED,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-display', Options::LOGIN_DISCLAIMER, array(
        'type'    => 'string',
        'default' => Defaults::LOGIN_DISCLAIMER,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::REGISTER_BTN_TEXT, array(
        'type'    => 'string',
        'default' => Defaults::REGISTER_BTN_TEXT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::LOGIN_BTN_TEXT, array(
        'type'    => 'string',
        'default' => Defaults::LOGIN_BTN_TEXT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::CREATE_BTN_TEXT, array(
        'type'    => 'string',
        'default' => Defaults::CREATE_BTN_TEXT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::CANCEL_BTN_TEXT, array(
        'type'    => 'string',
        'default' => Defaults::CANCEL_BTN_TEXT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::TICKET_CREATED_MSG, array(
        'type'    => 'string',
        'default' => Defaults::TICKET_CREATED_MSG,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::TICKET_UPDATED_MSG, array(
        'type'    => 'string',
        'default' => Defaults::TICKET_UPDATED_MSG,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::EMPTY_TABLE_MSG, array(
        'type'    => 'string',
        'default' => Defaults::EMPTY_TABLE_MSG,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::COMMENTS_CLOSED_MSG, array(
        'type'    => 'string',
        'default' => Defaults::COMMENTS_CLOSED_MSG,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::FOOTER_TEXT, array(
        'type'    => 'string',
        'default' => Defaults::FOOTER_TEXT,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-display', Options::LOGIN_WIDGET_AREA, array(
        'type'    => 'string',
        'default' => Defaults::LOGIN_WIDGET_AREA,
        'sanitize_callback' => 'wp_kses_post'
    ) );

    register_setting( 'uc-display', Options::AGENT_WIDGET_AREA, array(
        'type'    => 'string',
        'default' => Defaults::AGENT_WIDGET_AREA,
        'sanitize_callback' => 'wp_kses_post'
    ) );

    register_setting( 'uc-display', Options::USER_WIDGET_AREA, array(
        'type'    => 'string',
        'default' => Defaults::USER_WIDGET_AREA,
        'sanitize_callback' => 'wp_kses_post'
    ) );

    register_setting( 'uc-display', Options::QUICK_LINK_ENABLED, array(
        'type'    => 'string',
        'default' => Defaults::QUICK_LINK_ENABLED,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-display', Options::QUICK_LINK_LABEL, array(
        'type'    => 'string',
        'default' => Defaults::QUICK_LINK_LABEL,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-general', Options::LOGO, array(
        'type'    => 'string',
        'default' => resolve_url( 'assets/images/logo.png' ),
        'sanitize_callback' => 'esc_url_raw'
    ) );

    register_setting( 'uc-general', Options::FAVICON, array(
        'type'    => 'string',
        'default' => resolve_url( 'assets/images/favicon.png' ),
        'sanitize_callback' => 'esc_url_raw'
    ) );

    register_setting( 'uc-general', Options::COMPANY_NAME, array(
        'type'    => 'string',
        'default' => Defaults::COMPANY_NAME,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-general', Options::TERMS_URL, array(
        'type'    => 'string',
        'default' => home_url(),
        'sanitize_callback' => 'esc_url_raw'
    ) );

    register_setting( 'uc-general', Options::ALLOW_SIGNUPS, array(
        'type'    => 'string',
        'default' => Defaults::ALLOW_SIGNUPS,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-general', Options::MAX_TICKETS, array(
        'type'    => 'integer',
        'default' => Defaults::MAX_TICKETS,
        'sanitize_callback' => 'absint'
    ) );

    register_setting( 'uc-general', Options::IMAGE_MIME_TYPES, array(
        'type'    => 'string',
        'default' => Defaults::IMAGE_MIME_TYPES,
        'sanitize_callback' => 'sanitize_textarea_field'
    ) );

    register_setting( 'uc-general', Options::FILE_MIME_TYPES, array(
        'type'    => 'string',
        'default' => Defaults::FILE_MIME_TYPES,
        'sanitize_callback' => 'sanitize_textarea_field'
    ) );

    register_setting( 'uc-general', Options::MAX_ATTACHMENT_SIZE, array(
        'type'    => 'number',
        'default' => Defaults::MAX_ATTACHMENT_SIZE,
        'sanitize_callback' => 'abs'
    ) );

    register_setting( 'uc-general', Options::REFRESH_INTERVAL, array(
        'type'    => 'integer',
        'default' => Defaults::REFRESH_INTERVAL,
        'sanitize_callback' => 'abs'
    ) );

    register_setting( 'uc-general', Options::LOGGING_ENABLED, array(
        'type'    => 'string',
        'default' => Defaults::LOGGING_ENABLED,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-general', Options::ECOMMERCE, array(
        'type'    => 'string',
        'default' => Defaults::ECOMMERCE,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-general', Options::INACTIVE_MAX_AGE, array(
        'type'    => 'integer',
        'default' => Defaults::FILE_MIME_TYPES,
        'sanitize_callback' => 'abs'
    ) );

    register_setting( 'uc-general', Options::AUTO_CLOSE, array(
        'type'    => 'string',
        'default' => Defaults::AUTO_CLOSE,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-email', Options::WELCOME_EMAIL_TEMPLATE, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::TICKET_CLOSED_EMAIL_TEMPLATE, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::TICKET_CREATED_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::AGENT_REPLY_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::PASSWORD_RESET_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::INACTIVE_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::NEW_TICKET_ADMIN_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::CUSTOMER_REPLY_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::NEW_TICKET_ADMIN_EMAIL, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id'
    ) );

    register_setting( 'uc-email', Options::EMAIL_NOTIFICATIONS, array(
        'type'    => 'string',
        'default' => Defaults::EMAIL_NOTIFICATIONS,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-email', Options::ADMIN_EMAIL, array(
        'type'    => 'string',
        'default' => get_option( 'admin_email' ),
        'sanitize_callback' => 'sanitize_email'
    ) );

    register_setting( 'uc-email', Options::SENDER_EMAIL, array(
        'type'    => 'string',
        'default' => get_option( 'admin_email' ),
        'sanitize_callback' => 'sanitize_email'
    ) );

    register_setting( 'uc-email', Options::SENDER_NAME, array(
        'type'    => 'string',
        'default' => Defaults::SENDER_NAME,
        'sanitize_callback' => 'sanitize_text_field'
    ) );

    register_setting( 'uc-advanced', Options::DEV_MODE, array(
        'type'    => 'string',
        'default' => Defaults::DEV_MODE,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    register_setting( 'uc-advanced', Options::NUKE, array(
        'type'    => 'string',
        'default' => Defaults::NUKE,
        'sanitize_callback' => 'ucare\sanitize_boolean'
    ) );

    /**
     *
     * @since 1.6.0
     */
    register_setting( 'uc-advanced', Options::CREATE_TICKET_PAGE_ID, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id',
    ) );

    register_setting( 'uc-advanced', Options::EDIT_PROFILE_PAGE_ID, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id',
    ) );

    register_setting( 'uc-advanced', Options::LOGIN_PAGE_ID, array(
        'type' => 'integer',
        'sanitize_callback' => 'ucare\sanitize_post_id',
    ) );

    /**
     * @since 1.7.0
     */
    register_setting( 'uc-display', Options::LOGIN_TITLE, array(
        'type'              => 'string',
        'default'           => __( 'Get Support', 'ucare' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_setting( 'uc-display', Options::LOGIN_SUBTEXT, array(
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_setting( 'uc-display', Options::REGISTRATION_TITLE, array(
        'type'              => 'string',
        'default'           => __( 'Profile', 'ucare' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_setting( 'uc-display', Options::REGISTRATION_SUBTEXT, array(
        'type'              => 'string',
        'default'           => __( 'We just need a few more details to continue...', 'ucare' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_setting( 'uc-display', Options::TOS_TITLE, array(
        'type'              => 'string',
        'default'           => __( 'Terms of Service', 'ucare' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    register_setting( 'uc-advanced', Options::ENFORCE_TOS, array(
        'type'              => 'boolean',
        'default'           => true,
        'sanitize_callback' => 'ucare\sanitize_bool',
    ) );

    register_setting( 'uc-advanced', Options::TOS_POLICY, array(
        'type'              => 'string',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    register_setting( 'uc-advanced', Options::ADMIN_REDIRECT, array(
        'type'              => 'boolean',
        'default'           => true,
        'sanitize_callback' => 'ucare\sanitize_bool',
    ) );

    register_setting( 'uc-general', Options::MIN_PW_LENGTH, array(
        'type'              => 'integer',
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ) );
}
