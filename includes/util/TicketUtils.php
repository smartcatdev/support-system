<?php

namespace SmartcatSupport\util;


class TicketUtils {
    private function __construct() {}

    public static function ticket_ids() {
        return wp_list_pluck( get_posts( array( 'post_type' => 'support_ticket' ) ), 'ID' );
    }

    public static function ticket_author_email( $post ) {
        return get_user_by( 'ID', $post->post_author )->user_email;
    }
}
