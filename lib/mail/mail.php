<?php

// Check to make sure the mailer hasn't already been loaded
if( !function_exists( '\smartcat\mail\init' ) ) {

    include_once 'functions.php';
    include_once 'actions.php';
    include_once 'hooks.php';

}
