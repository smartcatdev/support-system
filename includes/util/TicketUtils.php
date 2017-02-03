<?php

namespace SmartcatSupport\util;


class TicketUtils {
    private function __construct() {}

    public static function comments_enabled( $ticket ) {
        $status = get_post_meta( $ticket, 'status', true );

        return $status !== 'closed' && $status !== 'resolved';
    }
}
