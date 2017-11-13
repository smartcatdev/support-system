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

