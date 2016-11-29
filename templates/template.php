<?php
//option allow users to register
get_header();

if( is_user_logged_in() && current_user_can( 'view_support_tickets' ) ) {
    include_once 'dash.php';
} else {
    include_once 'login.php';
}

get_footer();