<?php

do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );

include_once 'lib/mail/mail.php';

include_once 'includes/functions.php';
include_once 'includes/hooks.php';

include_once 'includes/hooks/actions.php';
include_once 'includes/hooks/filters.php';
