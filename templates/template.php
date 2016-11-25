<?php

get_header();

if( is_user_logged_in() && current_user_can( 'view_support_tickets' ) ) {
    include_once 'dash.php';
} else {
    wp_login_form();
}

get_footer();