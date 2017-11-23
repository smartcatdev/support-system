<?php
/**
 * New place for template related code
 *
 * @since 1.4.2
 */

namespace ucare;


add_action( 'init', 'ucare\register_menu_locations' );

add_action( 'login_form_bottom', 'ucare\add_support_login_field', 10, 2 );

add_action( 'login_form_bottom', 'ucare\add_login_registration_button', 10, 2 );

add_filter( 'template_include', 'ucare\include_support_template' );


function register_menu_locations() {

    $locations = array(
        'ucare_header_navbar' => __( 'uCare Navigation Menu', 'cdemo' ),
    );

    register_nav_menus( $locations );
}


function include_support_template( $template ) {

    if( get_the_ID() == get_option( Options::TEMPLATE_PAGE_ID ) ) {
        $template = get_template( 'app', null, false );
    }

    return $template;
}


function get_template( $name, $args = array(), $include = true, $once = true ) {

    $tmpl = false;
    $name = str_replace( '.php', '', $name ) . '.php';

    if ( file_exists( UCARE_TEMPLATES_PATH . $name ) ) {
        $tmpl = UCARE_TEMPLATES_PATH . $name;
    } else if ( file_exists( UCARE_PARTIALS_PATH . $name ) ) {
        $tmpl = UCARE_PARTIALS_PATH . $name;
    }

    if ( $tmpl ) {

        if ( $include ) {

            if ( is_array( $args ) ) {
                extract( $args );
            }

            if ( $once ) {
                include_once $tmpl;
            } else {
                include $tmpl;
            }

        }

        return $tmpl;

    }

    return false;

}


function buffer_template( $name, $args = array(), $once = true ) {

    ob_start();

    get_template( $name, $args, true, $once );

    return ob_get_clean();

}


function add_login_registration_button( $content, $args ) {

    if ( $args['form_id'] == 'support_login' &&
         get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS ) &&

         // Bypass check fif not passed in args
         ( !isset( $args['show_register_link'] ) || $args['show_register_link'] == true ) ) {

        $link_text = isset( $args['register_link_text'] ) ? $args['register_link_text'] : __( 'Register', 'ucare' );

        $content .= sprintf(
            '<p class="login-register"><a class="button button-primary" href="%1$s">%2$s</a></p>',
            esc_url( support_page_url( '?register=true' ) ),
            esc_html( $link_text )
        );

    }

    return $content;

}


function add_support_login_field( $content, $args ) {

    if ( $args['form_id'] == 'support_login' ) {
        $content .= '<input type="hidden" name="support_login_form" />';
    }

    return $content;

}

/**
 * Output underscore.js templates.
 *
 * @since 1.4.2
 * @return void
 */
function print_underscore_templates() {

    get_template( 'underscore/tmpl-confirm-modal' );
    get_template( 'underscore/tmpl-notice-inline' );
    get_template( 'underscore/tmpl-ajax-loader-mask' );

}


/**
 * Print copyright text with branding.
 *
 * @since 1.4.2
 * @return void
 */
function print_footer_copyright() {

    $text  = get_option( Options::FOOTER_TEXT );
    $brand = apply_filters( 'ucare_footer_branding', true );

    if ( $text ) {
        echo $text . ( $brand ? ' | ' : '' );
    }

    if ( $brand ) { ?>

        <a href="http://ucaresupport.com" target="_blank">
            <?php _e( 'Powered by uCare Support', 'ucare' ); ?>
        </a>

    <?php }

}
