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
include_once dirname( __FILE__ ) . '/includes/ticket-post-type.php';
include_once dirname( __FILE__ ) . '/includes/wp-admin.php';
include_once dirname( __FILE__ ) . '/includes/wp-widgets.php';

