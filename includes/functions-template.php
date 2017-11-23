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

// Redirect unauthorized users back to the login
add_action( 'template_redirect', 'ucare\maybe_do_auth_redirect' );

add_filter( 'template_include', 'ucare\include_page_template' );


function register_menu_locations() {

    $locations = array(
        'ucare_header_navbar' => __( 'uCare Navigation Menu', 'cdemo' ),
    );

    register_nav_menus( $locations );
}


/**
 * Include a custom page template.
 *
 * @param $template
 *
 * @since 1.0.0
 * @return string
 */
function include_page_template( $template ) {

    $id = get_the_ID();

    if( $id == get_option( Options::TEMPLATE_PAGE_ID ) ) {
        $template = get_template( 'app', null, false );
    } else if ( $id == get_option( Options::CREATE_TICKET_PAGE_ID ) ) {
        $template = get_template( 'create-ticket', null, false );
    }

    return $template;

}


/**
 * Redirect the user if they are logged out or unauthorized.
 *
 * @action template_redirect
 *
 * @since 1.5.1
 * @return void
 */
function maybe_do_auth_redirect() {

    // TODO separate login/registration page
    if ( is_create_ticket_page() && ( !is_user_logged_in() || !current_user_can( 'use_support' ) ) ) {
        wp_safe_redirect( get_the_permalink( get_option( Options::TEMPLATE_PAGE_ID ) ) );
    }

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
 * Check to see if a page belongs to the support system.
 *
 * @param mixed $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_a_support_page( $page = null ) {

    $page = get_post( $page );

    if ( $page ) {

        return $page->ID == get_option( Options::CREATE_TICKET_PAGE_ID ) ||
               $page->ID == get_option( Options::TEMPLATE_PAGE_ID );

    }

    return false;

}


/**
 * Check to see if the current page is the create ticket page.
 *
 * @param null $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_create_ticket_page( $page = null ) {

    $page = get_post( $page );

    if ( $page ) {
        return $page->ID == get_option( Options::CREATE_TICKET_PAGE_ID );
    }

    return false;

}


/**
 * Check to see if the current page is the support page.
 *
 * @param null $page
 *
 * @since 1.5.1
 * @return bool
 */
function is_support_page( $page = null ) {

    $page = get_post( $page );

    if ( $page ) {
        return $page->ID == get_option( Options::TEMPLATE_PAGE_ID );
    }

    return false;

}

/**
 * Get the support system header.
 *
 * @param array $args
 *
 * @since 1.5.1
 * @return void
 */
function get_header( $args = array() ) {

    get_template( 'header', $args );

}



/**
 * Get the support system footer.
 *
 * @param array $args
 *
 * @since 1.5.1
 * @return void
 */
function get_footer( $args = array() ) {

    get_template( 'footer', $args );

}
