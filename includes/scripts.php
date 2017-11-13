<?php

namespace ucare;


function init_scripts( uCare $ucare ) {

    $ucare->set( 'scripts', new \WP_Scripts() );
    $ucare->set( 'styles', new \WP_Styles() );

}

add_action( 'ucare_loaded', 'ucare\init_scripts' );


function enqueue_system_scripts() {

    if ( get_option( Options::TEMPLATE_PAGE_ID ) == get_the_ID() ) {
        do_action( 'ucare_enqueue_scripts' );
    }

}

add_action( 'wp', 'ucare\enqueue_system_scripts' );


function enqueue_script( $handle, $src, $deps = array(), $ver = false, $args = null ) {

    $scripts = scripts();

    if ( $scripts ) {
        $scripts->add( $handle, $src, $deps, $ver, $args );
        $scripts->enqueue( $handle );
    }

}


function enqueue_style( $handle, $src, $deps = array(), $ver = false, $args = null ) {

    $styles = styles();

    if ( $styles ) {
        $styles->add( $handle, $src, $deps, $ver, $args );
        $styles->enqueue( $handle );
    }

}

function print_header_scripts() {

    $scripts = scripts();

    if ( $scripts ) {

        $scripts->do_head_items();
        $scripts->reset();

        return $scripts->done;
    }

    return false;

}


function print_footer_scripts() {

    $scripts = scripts();

    if ( $scripts ) {

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
