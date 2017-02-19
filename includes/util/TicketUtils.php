<?php

namespace SmartcatSupport\util;


class TicketUtils {
    private function __construct() {}

    public static function ticket_author_email( $post ) {
        return get_user_by( 'ID', $post->post_author )->user_email;
    }
}
