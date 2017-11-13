<?php

do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    include_once dirname( __FILE__ ) . '/lib/license/EDD_SL_Plugin_Updater.php';
}

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'ucare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
} );


include_once dirname( __FILE__ ) . '/lib/mail/mail.php';
include_once dirname( __FILE__ ) . '/includes/functions.php';
include_once dirname( __FILE__ ) . '/includes/functions-public.php';
include_once dirname( __FILE__ ) . '/includes/ticket.php';
include_once dirname( __FILE__ ) . '/includes/comment.php';
include_once dirname( __FILE__ ) . '/includes/email-notifications.php';
include_once dirname( __FILE__ ) . '/includes/cron.php';
include_once dirname( __FILE__ ) . '/includes/extension-licensing.php';
include_once dirname( __FILE__ ) . '/includes/post-support_ticket.php';
include_once dirname( __FILE__ ) . '/includes/admin-menu.php';
include_once dirname( __FILE__ ) . '/includes/widgets.php';


/**
 * @since 1.4.2
 */
include_once dirname( __FILE__ ) . '/includes/class-field.php';
include_once dirname( __FILE__ ) . '/includes/class-bootstrap-nav-walker.php';
include_once dirname( __FILE__ ) . '/includes/template.php';
include_once dirname( __FILE__ ) . '/includes/sanitize.php';
include_once dirname( __FILE__ ) . '/includes/helpers.php';
include_once dirname( __FILE__ ) . '/includes/user.php';
include_once dirname( __FILE__ ) . '/includes/metabox.php';
include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
include_once dirname( __FILE__ ) . '/includes/taxonomy-ticket_category.php';
