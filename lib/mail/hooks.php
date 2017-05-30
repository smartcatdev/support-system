<?php

add_action( 'plugins_loaded', 'smartcat\mail\init' );

add_action( 'init', 'smartcat\mail\register_template_post_type' );

add_filter( 'user_can_richedit', 'smartcat\mail\disable_wsiwyg' );