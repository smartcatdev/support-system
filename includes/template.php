<?php

namespace ucare;


function enqueue_scripts() {

    wp_enqueue_style( 'ucare-login-form', plugin_url( 'assets/css/login.css' ), null, PLUGIN_VERSION );

}

add_action( 'wp_enqueue_scripts', 'ucare\enqueue_scripts' );


function register_menu_locations() {

    $locations = array(
        'ucare_header_navbar' => __( 'uCare Navigation Menu', 'cdemo' ),
    );

    register_nav_menus( $locations );
}

add_action( 'init', 'ucare\register_menu_locations' );


function shortcode_login_form() { ?>

    <?php if ( !is_user_logged_in() ) : ?>

        <?php wp_login_form( array( 'form_id' => 'support_login', 'redirect' => support_page_url() ) ); ?>

        <a href="<?php echo esc_url( add_query_arg( 'reset_password', 'true', support_page_url() ) ); ?>">
            <?php _e( 'Forgot password?', 'ucare' ); ?>
        </a>

    <?php else : ?>

        <a href="<?php echo esc_url( support_page_url() ); ?>">
            <?php esc_html_e( get_option( Options::LOGIN_SHORTCODE_TEXT, Defaults::LOGIN_SHORTCODE_TEXT ) ); ?>
        </a>

    <?php endif; ?>

<?php }

add_shortcode( 'support-login', 'ucare\shortcode_login_form' );



function add_login_registration_button( $content, $args ) {

    if ( get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS ) && $args['form_id'] == 'support_login' ) {

        $content .=
            '<p class="login-register">
                <a class="button button-primary" href="' . esc_url( support_page_url( '?register=true' ) ) . '">' . __( 'Register', 'ucare' ) . '</a>
            </p>';

    }

    return $content;

}

add_action( 'login_form_bottom', 'ucare\add_login_registration_button', 10, 2 );


function add_support_login_field( $content, $args ) {

    if ( $args['form_id'] == 'support_login' ) {
        $content .= '<input type="hidden" name="support_login_form" />';
    }

    return $content;

}

add_action( 'login_form_bottom', 'ucare\add_support_login_field', 10, 2 );