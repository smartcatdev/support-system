<?php
//option allow users to register


/*
 * 
 * 
 * Is the user logged in ?
 * -- navbar.php
 * -- dash.php
 * else 
 * -- login.php
 * 
 * 
 * 
 * 
 */


include_once SUPPORT_PATH . '/template-parts/header.php';

if( is_user_logged_in() && current_user_can( 'view_support_tickets' ) ) {
    include_once SUPPORT_PATH . '/template-parts/navbar.php';
    include_once 'dash.php';
} else {
    include_once 'login.php';
}

include_once SUPPORT_PATH . '/template-parts/footer.php';