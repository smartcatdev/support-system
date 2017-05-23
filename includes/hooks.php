<?php

namespace ucare\hooks;

// Register admin actions
add_action( 'support_options_admin_page_header', 'ucare\hooks\admin_header_cta' );
add_action( 'support_options_menu_page', 'ucare\hooks\admin_sidebar' );