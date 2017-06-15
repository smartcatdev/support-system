<?php

do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    include_once 'lib/license/EDD_SL_Plugin_Updater.php';
}

include_once 'lib/mail/mail.php';

include_once 'includes/functions.php';
include_once 'includes/functions-public.php';
include_once 'includes/default-filters.php';
include_once 'includes/extension-licensing.php';

