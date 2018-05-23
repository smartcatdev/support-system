<?php

namespace smartcat\mail;

// Check to make sure the mailer hasn't already been loaded
if( !function_exists( '\smartcat\mail\init' ) ) {

    function __( $text ) {
        return \__( $text, apply_filters( 'mailer_text_domain', '' ) );
    }

    function _x( $text, $context ) {
        return \_x( $text, $context, apply_filters( 'mailer_text_domain', '' ) );
    }

    function send_template( $template_id, $recipient, $replace = array(), $args = array() ) {
        $template = get_post( $template_id );
        $sent = false;
        
        if( !empty( $template ) ) {

            add_filter( 'mailer_template_vars', function ( $vars ) use ( $replace ) {
                return array_merge( $vars, $replace );
            } );

            $content = parse_template( $template->post_content, $template, $recipient );
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );

            $sent = wp_mail(
                $recipient,
                isset( $replace['ticket_number'] ) ? apply_filters( 'ucare_email_subject', $template->post_title, $replace['ticket_number'] ) : $template->post_title,
                wrap_template( $template, $content, $args ),
                apply_filters( 'mailer_email_headers', $headers, $template_id, $recipient )
            );
        }

        return $sent;
    }

    function parse_template( $content, $template, $recipient ) {
        
        $user = get_user_by( 'email', $recipient );
        
        $defaults = array(
            'username'       => \ucare\pluck( $user, 'user_login' ),
            'first_name'     => \ucare\pluck( $user, 'first_name' ),
            'last_name'      => \ucare\pluck( $user, 'last_name'  ),
            'full_name'      => \ucare\pluck( $user, 'display_name' ),
            'email'          => \ucare\pluck( $user, 'user_email', $recipient ),
            'template_title' => $template->post_title,
            'home_url'       => home_url()
        );

        $vars = apply_filters( 'mailer_template_vars', $defaults, $recipient, $template );

        foreach( $vars as $shortcode => $value ) {
            $content = stripcslashes( str_replace( '{%' . $shortcode . '%}', $value, $content ) );
        }

        return $content;
    }

    function wrap_template( $template, $content, $args ) {
        ob_start(); ?>
        <?php do_action( 'ucare_above_email' ); ?>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <style type="text/css"><?php echo wp_strip_all_tags( get_post_meta( $template->ID, 'styles', true ) ); ?></style>
            <style>
                .footer {
                    margin-top: 20px;
                    text-align: center;
                }
            </style>
            <?php do_action( 'ucare_email_header', $template, $args ); ?>
            <?php echo do_action( 'email_template_header', $template, $args ); ?>
        </head>
        <body>
            <?php echo $content; ?>
        <div class="footer">
            <?php do_action( 'ucare_email_footer', $template, $args ); ?>
            <?php echo do_action( 'email_template_footer', $template, $args ); ?>
        </div>
        </body>
        </html>

        <?php return ob_get_clean();
    }

    function list_templates() {
        global $wpdb;

        $results = array();
        $templates = $wpdb->get_results(
            "SELECT ID, post_title 
             FROM {$wpdb->prefix}posts 
             WHERE post_type='email_template' 
                AND post_status='publish'"
        );

        foreach( $templates as $template ) {
            $results[ $template->ID ] = $template ->post_title;
        }

        return $results;
    }


    function cleanup() {
        unregister_post_type( 'email_template' );
    }


    function disable_wsiwyg( $enabled ) {
        if( get_post_type() == 'email_template' ) {
            $enabled = false;
        }

        return $enabled;
    }

    add_filter( 'user_can_richedit', 'smartcat\mail\disable_wsiwyg' );

}
