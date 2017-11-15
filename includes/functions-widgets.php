<?php

namespace ucare;


add_action( 'wp_enqueue_scripts', 'ucare\enqueue_widget_scripts' );

add_action( 'wp_footer', 'ucare\do_quick_link_widget' );


function enqueue_widget_scripts() {

    wp_enqueue_style( 'ucare-widget-styles', resolve_url( 'assets/css/wp-widgets.css' ), null, PLUGIN_VERSION );

}


function do_quick_link_widget() {

    if ( get_option( Options::QUICK_LINK_ENABLED, Defaults::QUICK_LINK_ENABLED ) ) { ?>

        <div id="ucare-quick-link-widget">
            <a href="<?php echo esc_url( support_page_url() ); ?>"
               style="background-color: <?php echo esc_attr( get_option( Options::PRIMARY_COLOR, Defaults::PRIMARY_COLOR ) ); ?>">
                <?php echo esc_attr( get_option( Options::QUICK_LINK_LABEL, Defaults::QUICK_LINK_LABEL ) ); ?>
            </a>
        </div>

    <?php }

}
