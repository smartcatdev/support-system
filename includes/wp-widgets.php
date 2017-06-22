<?php

namespace ucare;

use ucare\descriptor\Option;

function enqueue_widget_scripts() {

    wp_enqueue_style( 'ucare-widget-styles', plugin_url( 'assets/css/wp-widgets.css' ), null, PLUGIN_VERSION );
    wp_enqueue_script( 'ucare-widget-scripts', plugin_url( 'assets/js/wp-widgets.js' ), array( 'jquery' ), PLUGIN_VERSION );

}

add_action( 'wp_enqueue_scripts', 'ucare\enqueue_widget_scripts' );


function do_quick_link_widget() {

    if( get_option( Option::QUICK_LINK_ENABLED, Option\Defaults::QUICK_LINK_ENABLED ) ) { ?>

        <div id="ucare-quick-link-widget">

            <a style="background-color: <?php echo esc_attr( get_option( Option::PRIMARY_COLOR, Option\Defaults::PRIMARY_COLOR ) ); ?>"
               href="<?php echo esc_url( url() ); ?>"><?php echo esc_attr( get_option( Option::QUICK_LINK_LABEL, Option\Defaults::QUICK_LINK_LABEL ) ); ?></a>

        </div>

    <?php }

}

add_action( 'wp_head', 'ucare\do_quick_link_widget' );
