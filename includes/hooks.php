<?php

namespace ucare\hooks;


// Include admin header
add_action( 'support_options_admin_page_header', 'ucare\hooks\admin_page_header' );


// Include admin sidebar on options page
add_action( 'support_options_menu_page', 'ucare\hooks\admin_page_sidebar' );


// Update the ticket modified on status changes
add_action( 'update_post_metadata', 'ucare\hooks\ticket_updated', 10, 4 );

// Update the comment status after a comment has been made
add_action( 'comment_post', 'ucare\hooks\comment_save' );
add_action( 'edit_comment', 'ucare\hooks\comment_save' );


// Add actions for ticket auto close cron jobs
add_action( 'ucare_cron_stale_tickets', 'ucare\hooks\mark_stale_tickets' );
add_action( 'ucare_cron_close_tickets', 'ucare\hooks\close_stale_tickets' );
