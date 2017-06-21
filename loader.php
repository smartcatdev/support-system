<?php

do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    include_once 'lib/license/EDD_SL_Plugin_Updater.php';
}

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'ucare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
} );


include_once 'lib/mail/mail.php';

include_once 'includes/functions.php';
include_once 'includes/functions-public.php';
include_once 'includes/ticket.php';
include_once 'includes/comment.php';
include_once 'includes/email-notifications.php';
include_once 'includes/cron.php';
include_once 'includes/extension-licensing.php';
include_once 'includes/ticket-post-type.php';
include_once 'includes/admin-pages.php';

