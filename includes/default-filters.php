<?php

namespace ucare;


add_action( 'admin_enqueue_scripts', 'ucare\enqueue_admin_scripts' );


// Include admin header
add_action( 'support_options_admin_page_header', 'ucare\admin_page_header' );


// Include admin sidebar on options page
add_action( 'support_options_menu_page', 'ucare\admin_page_sidebar' );


// Update the ticket modified on status changes
add_action( 'update_post_metadata', 'ucare\ticket_updated', 10, 4 );


// Update the comment status after a comment has been made
add_action( 'comment_post', 'ucare\comment_save' );
add_action( 'edit_comment', 'ucare\comment_save' );


// Add actions for ticket auto close cron jobs
add_action( 'ucare_cron_stale_tickets', 'ucare\mark_stale_tickets' );
add_action( 'ucare_cron_close_tickets', 'ucare\close_stale_tickets' );
