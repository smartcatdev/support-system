<?php
/**
 * Functions for managing the UI toolbar.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


add_action( 'ucare_loaded', 'ucare\init_toolbar' );


/**
 * Initialize the global toolbar.
 *
 * @action ucare_loaded
 *
 * @param uCare $ucare
 *
 * @since 1.6.0
 * @return void
 */
function init_toolbar( $ucare ) {
    $toolbar = new Toolbar();
    $toolbar->initialize();
    $ucare->set( 'the_toolbar', $toolbar );
}


/**
 * Output the global toolbar
 *
 * @since 1.6.0
 * @return void
 */
function the_toolbar() {
    $toolbar = ucare()->get( 'the_toolbar' );

    if ( $toolbar ) {
        $toolbar->render();
    }
}
