<?php

namespace smartcat\mail;

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
            $template->post_title,
            wrap_template( $template, $content, $args ),
            apply_filters( 'mailer_email_headers', $headers, $template_id, $recipient )
        );
    }

    return $sent;
}

function parse_template( $content, $template, $recipient ) {
    $user = get_user_by( 'email', $recipient );

    $defaults = array(
        'username'       => $user->user_login,
        'first_name'     => $user->first_name,
        'last_name'      => $user->last_name,
        'full_name'      => $user->first_name . ' ' . $user->last_name,
        'template_title' => $template->post_title,
        'email'          => !empty( $user ) ? $user->user_email : $recipient,
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
            <?php echo do_action( 'email_template_header', $template, $args ); ?>
        </head>
        <body>
            <?php echo $content; ?>
            <div class="footer">
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

function add_caps() {
    $administrator = get_role( 'administrator' );

    $administrator->add_cap( 'read_email_template' );
    $administrator->add_cap( 'read_email_templates' );
    $administrator->add_cap( 'edit_email_template' );
    $administrator->add_cap( 'edit_email_templates' );
    $administrator->add_cap( 'edit_others_email_templates' );
    $administrator->add_cap( 'edit_published_email_templates' );
    $administrator->add_cap( 'publish_email_templates' );
    $administrator->add_cap( 'delete_others_email_templates' );
    $administrator->add_cap( 'delete_private_email_templates' );
    $administrator->add_cap( 'delete_published_email_templates' );
}

function remove_caps() {
    $administrator = get_role( 'administrator' );

    $administrator->remove_cap( 'read_email_template' );
    $administrator->remove_cap( 'read_email_templates' );
    $administrator->remove_cap( 'edit_email_template' );
    $administrator->remove_cap( 'edit_email_templates' );
    $administrator->remove_cap( 'edit_others_email_templates' );
    $administrator->remove_cap( 'edit_published_email_templates' );
    $administrator->remove_cap( 'publish_email_templates' );
    $administrator->remove_cap( 'delete_others_email_templates' );
    $administrator->remove_cap( 'delete_private_email_templates' );
    $administrator->remove_cap( 'delete_published_email_templates' );
}

function cleanup() {
    remove_caps();
    unregister_post_type( 'email_template' );
}
