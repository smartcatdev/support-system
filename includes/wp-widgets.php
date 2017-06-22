<?php

namespace ucare;

use ucare\Options;

function enqueue_widget_scripts() {

    wp_enqueue_style( 'ucare-widget-styles', plugin_url( 'assets/css/wp-widgets.css' ), null, PLUGIN_VERSION );
    wp_enqueue_script( 'ucare-widget-scripts', plugin_url( 'assets/js/wp-widgets.js' ), array( 'jquery' ), PLUGIN_VERSION );

}

add_action( 'wp_enqueue_scripts', 'ucare\enqueue_widget_scripts' );


function do_quick_link_widget() {

    if( get_option( Options::QUICK_LINK_ENABLED, \ucare\Defaults::QUICK_LINK_ENABLED ) ) { ?>

        <div id="ucare-quick-link-widget">

            <p>

                <a href="<?php echo support_page_url(); ?>">

                    <?php echo get_option( Options::QUICK_LINK_LABEL, \ucare\Defaults::QUICK_LINK_LABEL ); ?>

                </a>

            </p>

        </div>

    <?php }

}

add_action( 'wp_head', 'ucare\do_quick_link_widget' );
