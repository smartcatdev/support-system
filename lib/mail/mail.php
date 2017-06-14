<?php

namespace smartcat\mail;

// Check to make sure the mailer hasn't already been loaded
if( !function_exists( '\smartcat\mail\init' ) ) {

    include_once 'functions.php';

    add_action( 'plugins_loaded', 'smartcat\mail\init' );

    add_action( 'init', 'smartcat\mail\register_template_post_type' );

    add_filter( 'user_can_richedit', 'smartcat\mail\disable_wsiwyg' );

}
