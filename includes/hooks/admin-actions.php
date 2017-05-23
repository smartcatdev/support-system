<?php

namespace ucare\hooks;

function admin_header_cta() {
    include_once \ucare\plugin_dir() . '/templates/admin-header.php';
}

function admin_sidebar() {
    include_once \ucare\plugin_dir() . '/templates/admin-sidebar.php';
}