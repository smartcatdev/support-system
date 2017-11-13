<?php
/**
 * New place for template related code
 *
 * @since 1.4.2
 */

namespace ucare;


function register_menu_locations() {

    $locations = array(
        'ucare_header_navbar' => __( 'uCare Navigation Menu', 'cdemo' ),
    );

    register_nav_menus( $locations );
}

add_action( 'init', 'ucare\register_menu_locations' );


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
