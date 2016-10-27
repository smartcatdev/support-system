<?php

namespace SmartcatSupport\controller;

use SmartcatSupport\util\ActionListener;
use SmartcatSupport\util\View;

class TicketTableHandler extends ActionListener  {
    private $view;

    public function __construct( View $view ) {
        $this->view = $view;

        $this->add_ajax_action( 'list_support_tickets', 'ticket_table' );
        $this->add_ajax_action( 'get_support_tickets', 'get_tickets' );
    }

    public function ticket_table() {
        $headers = [
            'id' => 'ID',
            'email' => 'Email',
            'subject' => 'Subject'
        ];

        wp_send_json( [
            'html' => $this->view->render( 'table',
                [
                    'id' => 'support_tickets_table',
                    'headers' => apply_filters( 'support_ticket_table_headers', $headers ),
                    'data' => $this->get_tickets()
                ]
            )
        ] );
    }

    public function get_tickets() {
        $query = [
            'post_type' => 'support_ticket',
            'status'    => 'publish',
        ];

        $results = new \WP_Query( $query );

        $ticket_data = [];

        while( $results->have_posts() ) {

            $results->the_post();

            $ticket_data[] = [
                'id' => get_the_ID(),
                'email' => get_post_meta( $results->post->ID, 'email', true ),
                'subject' => get_the_title()
            ];
        }

        wp_reset_postdata();

        return $ticket_data;
    }
}