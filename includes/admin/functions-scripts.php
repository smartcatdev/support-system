<?php
/**
 * Functions for managing admin scripts.
 *
 * @since 1.2.0
 * @package ucare
 */
namespace ucare;


// Register admin dependencies
add_action( 'admin_enqueue_scripts', fqn( 'register_admin_dependencies' ) );


/**
 * Register dependencies for the WordPress administration screens
 *
 * @action admin_enqueue_scripts
 *
 * @since 1.6.1
 * @return void
 */
function register_admin_dependencies() {
    
    // Register React dependencies
    wp_register_script( 'redux', resolve_url( 'assets/js/redux/redux.min.js' ), null, PLUGIN_VERSION );
    wp_register_script( 'react', resolve_url( 'assets/js/react/react.min.js' ), null, PLUGIN_VERSION );
    wp_register_script( 'react-dom',   resolve_url( 'assets/js/react-dom/react-dom.min.js' ),     array( 'react' ),          PLUGIN_VERSION );
    wp_register_script( 'react-redux', resolve_url( 'assets/js/react-redux/react-redux.min.js' ), array( 'react', 'redux' ), PLUGIN_VERSION );
}
