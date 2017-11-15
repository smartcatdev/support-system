<?php

namespace ucare;


add_action( 'ucare_loaded', 'ucare\init_scripts' );

add_action( 'wp', 'ucare\enqueue_system_scripts' );

add_action( 'ucare_enqueue_scripts', 'ucare\enqueue_default_scripts' );


function init_scripts( uCare $ucare ) {

    $ucare->set( 'scripts', new \WP_Scripts() );
    $ucare->set( 'styles', new \WP_Styles() );

}


function enqueue_system_scripts() {

    if ( get_option( Options::TEMPLATE_PAGE_ID ) == get_the_ID() ) {
        do_action( 'ucare_enqueue_scripts' );
    }

}


function enqueue_default_scripts() {

}


function enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $args = null ) {

    $scripts = scripts();

    if ( $scripts ) {

        if ( $src ) {
            register_script( $handle, $src, $deps, $ver, $args );
        }

        return $scripts->enqueue( $handle );

    }

    return false;

}


function enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $args = null ) {

    $styles = styles();

    if ( $styles ) {

        if ( $src ) {
            register_style( $handle, $src, $deps = array(), $ver = false, $args = null );
        }

        return $styles->enqueue( $handle );

    }

    return false;

}


function register_script( $handle, $src, $deps = array(), $ver = false, $args = null ) {

    $scripts = scripts();

    if ( $scripts ) {
        return $scripts->add( $handle, $src, $deps, $ver, $args );
    }

    return false;

}


function localize_script( $handle, $object_name, $i10n ) {

    $scripts = scripts();

    if ( $scripts ) {
        $scripts->localize( $handle, $object_name, $i10n );
    }

    return false;

}


function register_style( $handle, $src, $deps = array(), $ver = false, $args = null ) {

    $styles = styles();

    if ( $styles ) {
        return $styles->add( $handle, $src, $deps, $ver, $args );
    }

    return true;

}


function print_header_scripts() {

    $scripts = scripts();

    if ( $scripts && !did_action( 'ucare_print_header_scripts' ) ) {

        do_action( 'ucare_print_header_scripts' );

        $scripts->do_head_items();
        $scripts->reset();

        return $scripts->done;
    }

    return false;

}


function print_footer_scripts() {

    $scripts = scripts();

    if ( $scripts && !did_action( 'ucare_print_footer_scripts' ) ) {

        do_action( 'ucare_print_footer_scripts' );

        $scripts->do_footer_items();
        $scripts->reset();

        return $scripts->done;

    }

    return false;

}


function print_styles() {

    $styles = styles();

    if ( $styles ) {

        $styles->do_items();
        $styles->reset();

        return $styles->done;

    }

    return false;

}


function scripts() {
    return ucare()->get( 'scripts' );
}


function styles() {
    return ucare()->get( 'styles' );
}
