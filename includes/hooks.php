<?php

namespace ucare\hooks;

// Include admin header
add_action( 'support_options_admin_page_header', function () {
    include_once \ucare\plugin_dir() . '/templates/admin-header.php';
} );

// Include admin sidebar on options page
add_action( 'support_options_menu_page', function () {
    include_once \ucare\plugin_dir() . '/templates/admin-sidebar.php';
} );
