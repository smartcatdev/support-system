<?php
/**
 * Functions for widgets to display on the front-end of the site.
 *
 * @package ucare
 * @since 1.4.2
 */
namespace ucare;


// Enqueue widget scripts to load on the front-end
add_action( 'wp_enqueue_scripts', 'ucare\enqueue_widget_scripts' );

// Print quick link widget in the site footers
add_action( 'wp_footer', 'ucare\do_quick_link_widget' );


/**
 * Get the rendered ticket widget areas.
 *
 * @param \WP_Post $ticket
 *
 * @since 1.6.0
 * @return array
 */
function get_ticket_widget_areas( $ticket ) {
    $widgets = array(
         'after_comments' => after_comments( $ticket )
    );

    return $widgets;
}


/**
 * Action to enqueue widget scripts.
 *
 * @action wp_enqueue_scripts
 *
 * @since 1.4.2
 * @return void
 */
function enqueue_widget_scripts() {
    wp_enqueue_style( 'ucare-widget-styles', resolve_url( 'assets/css/wp-widgets.css' ), null, PLUGIN_VERSION );
}


/**
 * Action to output the support quick-link widget.
 *
 * @action wp_footer
 *
 * @since 1.3.0
 * @return void
 */
function do_quick_link_widget() {

    if ( get_option( Options::QUICK_LINK_ENABLED, Defaults::QUICK_LINK_ENABLED ) && !is_login_page() ) { ?>

        <div id="ucare-quick-link-widget">
            <a href="<?php echo esc_url( support_page_url() ); ?>"
               style="background-color: <?php echo esc_attr( get_option( Options::PRIMARY_COLOR, Defaults::PRIMARY_COLOR ) ); ?>">
                <?php echo esc_attr( get_option( Options::QUICK_LINK_LABEL, Defaults::QUICK_LINK_LABEL ) ); ?>
            </a>
        </div>

    <?php }

}
