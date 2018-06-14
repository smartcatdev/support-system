<?php
/**
 * Functions related to the WordPress Settings API.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Add settings fields
add_action( 'admin_init', 'ucare\add_settings_fields', 100 );


/**
 * Action to add settings fields to be output in the settings page.
 *
 * @action admin_init
 *
 * @since 1.4.2
 * @return void
 */
function add_settings_fields() {

    /**
     *
     * General settings
     */
    add_settings_field(
        Options::MIN_PW_LENGTH,
        __( 'Minimum Password Length', 'ucare' ),
        'ucare\settings_text_field',
        'uc-general',
        'uc_general',
        array(
            'label_for' => Options::MIN_PW_LENGTH,
            'attrs'     => array(
                'id'    => Options::MIN_PW_LENGTH,
                'name'  => Options::MIN_PW_LENGTH,
                'type'  => 'number',
                'value' => get_option( Options::MIN_PW_LENGTH )
            )
        )
    );

    add_settings_field(
        'ucare-ecommerce-support',
        __( 'Enable eCommerce Support', 'ucare' ),
        'ucare\render_checkbox',
        'uc-general',
        'uc_general',
        array(
            'description' => __( 'Enable eCommerce support with Easy Digital Downloads or WooCommerce', 'ucare' ),
            'config' => array(
                'is_checked' => sanitize_boolean( get_option( Options::ECOMMERCE ) )
            ),
            'attributes' => array(
                'id'    => 'ucare-ecommerce-support',
                'name'  => Options::ECOMMERCE,
            )
        )
    );

    if ( ucare_ecommerce_mode() !== 'edd' ) {
        add_settings_field(
            Options::ALLOW_SUBSCRIBERS,
            __( 'Allow Subscribers', 'ucare' ),
            'ucare\settings_checkbox',
            'uc-general',
            'uc_general',
            array(
                'label'      => __( 'Allow users with the subscriber roll to access the help desk', 'ucare' ),
                'label_for'  => Options::ALLOW_SUBSCRIBERS,
                'is_checked' => (bool) get_option( Options::ALLOW_SUBSCRIBERS ),
                'attrs'      => array(
                    'id'      => Options::ALLOW_SUBSCRIBERS,
                    'name'    => Options::ALLOW_SUBSCRIBERS
                )
            )
        );
    }

    /**
     *
     * Advanced settings
     */
    add_settings_field(
        Options::ADMIN_REDIRECT,
        __( 'Redirect Admin Requests', 'ucare' ),
        'ucare\settings_checkbox',
        'uc-advanced',
        'uc_advanced',
        array(
            'label'      => __( 'Prevent support users and agents from accessing the admin area', 'ucare' ),
            'label_for'  => Options::ADMIN_REDIRECT,
            'is_checked' => (bool) get_option( Options::ADMIN_REDIRECT ),
            'attrs'      => array(
                'id'      => Options::ADMIN_REDIRECT,
                'name'    => Options::ADMIN_REDIRECT
            )
        )
    );

    add_settings_field(
        'ucare-template-page',
        __( 'Support Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-template-page',
            'value'      => get_option( Options::TEMPLATE_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::TEMPLATE_PAGE_ID,
                'class' => 'regular-text'
            ),
            'config' => array(
                'options' => array(
                    array(
                        'title'      => __( 'Select a Page', 'ucare' ),
                        'attributes' => array(
                            'value' => ''
                        )
                    )
                ),
                'wp_query' => array(
                    'post_type' => 'page',
                )
            )
        )
    );

    add_settings_field(
        'ucare-create-ticket-page',
        __( 'Create Ticket Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-create-ticket-page',
            'value'      => get_option( Options::CREATE_TICKET_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::CREATE_TICKET_PAGE_ID,
                'class' => 'regular-text'
            ),
            'config' => array(
                'options' => array(
                    array(
                        'title'      => __( 'Select a Page', 'ucare' ),
                        'attributes' => array(
                            'value' => ''
                        )
                    )
                ),
                'wp_query' => array(
                    'post_type' => 'page',
                )
            )
        )
    );

    add_settings_field(
        'ucare-edit-profile-page',
        __( 'Edit Profile Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-edit-profile-page',
            'value'      => get_option( Options::EDIT_PROFILE_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::EDIT_PROFILE_PAGE_ID,
                'class' => 'regular-text'
            ),
            'config' => array(
                'options' => array(
                    array(
                        'title'      => __( 'Select a Page', 'ucare' ),
                        'attributes' => array(
                            'value' => ''
                        )
                    )
                ),
                'wp_query' => array(
                    'post_type' => 'page',
                )
            )
        )
    );

    add_settings_field(
        'ucare-login-page',
        __( 'Login Page', 'ucare' ),
        'ucare\render_posts_dropdown',
        'uc-advanced',
        'uc_advanced',
        array(
            'id'         => 'ucare-login-page',
            'value'      => get_option( Options::LOGIN_PAGE_ID ),
            'attributes' => array(
                'name'  => Options::LOGIN_PAGE_ID,
                'class' => 'regular-text'
            ),
            'config' => array(
                'options' => array(
                    array(
                        'title'      => __( 'Select a Page', 'ucare' ),
                        'attributes' => array(
                            'value' => ''
                        )
                    )
                ),
                'wp_query' => array(
                    'post_type' => 'page',
                )
            )
        )
    );

    /**
     * Display settings
     */
    add_settings_field(
        Options::LOGIN_TITLE,
        __( 'Login Form Title', 'ucare' ),
        'ucare\settings_text_field',
        'uc-display',
        'uc_text',
        array(
            'label_for' => Options::LOGIN_TITLE,
            'attrs'     => array(
                'id'    => Options::LOGIN_TITLE,
                'name'  => Options::LOGIN_TITLE,
                'type'  => 'text',
                'class' => 'regular-text',
                'value' => get_option( Options::LOGIN_TITLE )
            )
        )
    );
    add_settings_field(
        Options::LOGIN_SUBTEXT,
        __( 'Login Form Subtext', 'ucare' ),
        'ucare\settings_textarea',
        'uc-display',
        'uc_text',
        array(
            'label_for' => Options::LOGIN_SUBTEXT,
            'value'     => get_option( Options::LOGIN_SUBTEXT ),
            'attrs'     => array(
                'id'    => Options::LOGIN_SUBTEXT,
                'name'  => Options::LOGIN_SUBTEXT,
                'class' => 'regular-text'
            )
        )
    );
    add_settings_field(
        Options::REGISTRATION_TITLE,
        __( 'Registration Title', 'ucare' ),
        'ucare\settings_text_field',
        'uc-display',
        'uc_text',
        array(
            'label_for' => Options::REGISTRATION_TITLE,
            'attrs'     => array(
                'id'    => Options::REGISTRATION_TITLE,
                'name'  => Options::REGISTRATION_TITLE,
                'class' => 'regular-text',
                'type'  => 'text',
                'value' => get_option( Options::REGISTRATION_TITLE )
            )
        )
    );
    add_settings_field(
        Options::REGISTRATION_SUBTEXT,
        __( 'Registration Form Subtext', 'ucare' ),
        'ucare\settings_textarea',
        'uc-display',
        'uc_text',
        array(
            'label_for' => Options::REGISTRATION_SUBTEXT,
            'value'     => get_option( Options::REGISTRATION_SUBTEXT ),
            'attrs'     => array(
                'id'    => Options::REGISTRATION_SUBTEXT,
                'name'  => Options::REGISTRATION_SUBTEXT,
                'class' => 'regular-text',
            )
        )
    );
    add_settings_field(
        Options::TOS_TITLE,
        __( 'TOS Title', 'ucare' ),
        'ucare\settings_text_field',
        'uc-display',
        'uc_text',
        array(
            'label_for' => Options::TOS_TITLE,
            'attrs'     => array(
                'id'    => Options::TOS_TITLE,
                'name'  => Options::TOS_TITLE,
                'class' => 'regular-text',
                'type'  => 'text',
                'value' => get_option( Options::TOS_TITLE )
            )
        )
    );
    add_settings_field(
        Options::RECEIPT_ID_LABEL,
        __( 'Receipt ID Label', 'ucare' ),
        'ucare\render_text_field',
        'uc-display',
        'uc_text',
        array(
            'id'         => Options::RECEIPT_ID_LABEL,
            'attributes' => array(
                'name'  => Options::RECEIPT_ID_LABEL,
                'class' => 'regular-text',
                'value' => get_option( Options::RECEIPT_ID_LABEL ),
            )
        )
    );

    /**
     * Privacy Settings
     */
    add_settings_field(
        Options::TOS_POLICY,
        __( 'Terms of Service', 'ucare' ),
        'ucare\settings_editor_field',
        'uc-privacy',
        'general',
        array(
            'label_for' => Options::TOS_POLICY,
            'value'     => get_option( Options::TOS_POLICY ),
            'attrs'     => array(
                'id'            => Options::TOS_POLICY,
                'textarea_name' => Options::TOS_POLICY,
                'editor_class'  => 'regular-text',
                'textarea_rows' => 5
            ),
            'description' => __( 'CAUTION: Making changes to this will require all users to re-accept this agreement', 'ucare' )
        )
    );
    add_settings_field(
        Options::ENFORCE_TOS,
        __( 'Enforce Terms', 'ucare' ),
        'ucare\settings_checkbox',
        'uc-privacy',
        'general',
        array(
            'label'      => __( 'Users must agree to terms before registering', 'kb' ),
            'label_for'  => Options::ENFORCE_TOS,
            'is_checked' => (bool) get_option( Options::ENFORCE_TOS ),
            'attrs'      => array(
                'id'      => Options::ENFORCE_TOS,
                'name'    => Options::ENFORCE_TOS
            )
        )
    );

    /**
     * Appearance Settings
     */
    add_settings_field(
        Options::LOAD_THEME_ASSETS,
        __( 'Load Theme Assets', 'ucare' ),
        'ucare\settings_checkbox',
        'uc-appearance',
        'uc_appearance',
        array(
            'label'      => __( 'Load theme assets on public pages', 'ucare' ),
            'label_for'  => Options::LOAD_THEME_ASSETS,
            'is_checked' => (bool) get_option( Options::LOAD_THEME_ASSETS ),
            'attrs'      => array(
                'id'      => Options::LOAD_THEME_ASSETS,
                'name'    => Options::LOAD_THEME_ASSETS
            )
        )
    );
}


/**
 * Output a text field
 *
 * @param array $args
 *
 * @since 1.7.0
 * @return void
 */
function settings_text_field( $args ) { ?>
    <input <?php echo parse_attributes( pluck( $args, 'attrs', array() ) ); ?> />
    <?php if ( !empty( $args['description'] ) ) : ?>
        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
    <?php endif; ?>
<?php }

/**
 * Output a textarea
 *
 * @param array $args
 *
 * @since 1.7.0
 * @return void
 */
function settings_textarea( $args ) { ?>
    <textarea <?php echo parse_attributes( pluck( $args, 'attrs', array() ) ); ?>
    ><?php echo esc_textarea( pluck( $args, 'value' ) ); ?></textarea>
    <?php if ( !empty( $args['description'] ) ) : ?>
        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
    <?php endif; ?>
<?php }

/**
 * Output a checkbox
 *
 * @param array $args
 *
 * @since 1.7.0
 * @return void
 */
function settings_checkbox( $args ) { ?>
    <label>
        <input type="checkbox" <?php echo parse_attributes( pluck( $args, 'attrs', array() ) ); ?>
            <?php checked( true, pluck( $args, 'is_checked' ) ); ?>
        />
        <?php esc_html_e( pluck( $args, 'label' ) ); ?>
    </label>
<?php }

/**
 * Output an MCE editor field
 *
 * @param array $args
 *
 * @since 1.7.0
 * @return void
 */
function settings_editor_field( $args ) {
    $attrs = pluck( $args, 'attrs' ); ?>
    <?php wp_editor( pluck( $args, 'value' ), pluck( $attrs, 'id' ), $args ); ?>
    <?php if ( !empty( $args['description'] ) ) : ?>
        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>
    <?php endif; ?>
<?php }