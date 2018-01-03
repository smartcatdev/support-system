<?php
/**
 * General purpose template functions and utilities.
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


add_action( 'login_form_bottom', 'ucare\add_support_login_field', 10, 2 );

add_action( 'login_form_bottom', 'ucare\add_login_registration_button', 10, 2 );


/**
 * Retrieve a template from the templates/ directory. Arguments passed will be globally available in the template file.
 * Template files are included by default if the template is found. This function also optionally returns a configured
 * closure that can be executed at a later point.
 *
 * @param string $name     The name of the template (without the file extension).
 * @param array  $args     (Optional) Arguments to be passed to the template.
 * @param bool   $include  Whether or not the template file should be included.
 * @param bool   $once     Whether to include or include_once.
 * @param bool   $execute  Execute the include right away. When set to false, the internal closure will be returned to
 *                         so that the include can be executed at a later point.
 * @param object $bind    (Optional) The object of which the internal closure should bind $this to.
 *
 * @since 1.6.0
 * @return bool|string
 */
function get_template( $name, $args = array(), $include = true, $once = true, $execute = true, $bind = null ) {

    $template = false;
    $name = str_replace( '.php', '', $name ) . '.php';

    // Check root templates and partials path.
    if ( file_exists( UCARE_TEMPLATES_PATH . $name ) ) {
        $template = UCARE_TEMPLATES_PATH . $name;
    } else if ( file_exists( UCARE_PARTIALS_PATH . $name ) ) {
        $template = UCARE_PARTIALS_PATH . $name;
    }

    // If the template  path is found
    if ( $template ) {

        // If we are to execute an include of the template file
        if ( $include ) {

            // Create a new closure
            $exec = function ( $args ) use ( $template, $once ) {

                // Extract args in scope of closure
                if ( is_array( $args ) ) {
                    extract( $args );
                }

                if ( $once ) {
                    include_once $template;
                } else {
                    include $template;
                }

            };

            // Bind new $this to the closure
            if ( is_object( $bind ) ) {
                $exec = \Closure::bind( $exec, $bind, $bind );
            }

            // If we are executing, pass in any args we were given
            if ( $execute ) {
                $exec( $args );

                // Else return the closure
            } else {
                return $exec;
            }

        }

        // Return template path
        return $template;

    }

    // The template wasn't found
    return false;

}


function buffer_template( $name, $args = array(), $once = true ) {

    ob_start();
    get_template( $name, $args, true, $once );

    return ob_get_clean();

}


function add_login_registration_button( $content, $args ) {

    if ( $args['form_id'] == 'support_login' &&
         get_option( Options::ALLOW_SIGNUPS, Defaults::ALLOW_SIGNUPS ) &&

         // Bypass check fif not passed in args
         ( !isset( $args['show_register_link'] ) || $args['show_register_link'] == true ) ) {

        $link_text = isset( $args['register_link_text'] ) ? $args['register_link_text'] : __( 'Register', 'ucare' );

        $content .= sprintf(
            '<p class="login-register"><a class="button button-primary" href="%1$s">%2$s</a></p>',
            esc_url( support_page_url( '?register=true' ) ),
            esc_html( $link_text )
        );

    }

    return $content;

}


function add_support_login_field( $content, $args ) {

    if ( $args['form_id'] == 'support_login' ) {
        $content .= '<input type="hidden" name="support_login_form" />';
    }

    return $content;

}


/**
 * Output underscore.js templates.
 *
 * @since 1.4.2
 * @return void
 */
function print_underscore_templates() {

    get_template( 'underscore/tmpl-confirm-modal' );
    get_template( 'underscore/tmpl-notice-inline' );
    get_template( 'underscore/tmpl-ajax-loader-mask' );

}


/**
 * Print copyright text with branding.
 *
 * @since 1.4.2
 * @return void
 */
function print_footer_copyright() {

    $text  = get_option( Options::FOOTER_TEXT );
    $brand = apply_filters( 'ucare_footer_branding', true );

    if ( $text ) {
        echo $text . ( $brand ? ' | ' : '' );
    }

    if ( $brand ) { ?>

        <a href="http://ucaresupport.com" target="_blank">
            <?php _e( 'Powered by uCare Support', 'ucare' ); ?>
        </a>

    <?php }

}