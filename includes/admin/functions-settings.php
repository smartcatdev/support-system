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


    /**
     *
     * Advanced settings
     */
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