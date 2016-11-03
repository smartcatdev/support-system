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

        $this->add_action( 'support_ticket_table_email_col', 'email_col', 10, 2 );
        $this->add_action( 'support_ticket_table_status_col', 'status_col', 10, 2 );
        $this->add_action( 'support_ticket_table_subject_col', 'subject_col', 10, 2 );
    }

    public function ticket_table() {
        wp_send_json( [
            'html' => $this->view->render( 'table',
                [
                    'id'      => 'support_tickets_table',
                    'headers' => $this->table_headers(),
                    'data'    => $this->table_data()
                ]
            )
        ] );
    }

    public function table_data() {
        $query = [
            'post_type' => 'support_ticket',
            'status'    => 'publish',
        ];

        $results = new \WP_Query( $query );

        $rows = [];

        while( $results->have_posts() ) {
            $results->the_post();

            $data = [];

            foreach( array_keys( $this->table_headers() ) as $col ) {
                $data[ $col ] = apply_filters( "support_ticket_table_{$col}_col", $results->post->ID, $results->post );
            }

            $rows[] = $data;

        }

        wp_reset_postdata();

        return $rows;
    }

    private function table_headers() {
        $headers = [
            'id'        => 'ID',
            'email'     => 'Email',
            'subject'   => 'Subject',
            'status'    => 'Status'
        ];

        return apply_filters( 'support_ticket_table_headers', $headers );
    }

    public function email_col( $post_id, $post ) {
        return get_post_meta( $post_id, 'email', true );
    }

    public function subject_col( $post_id, $post ) {
        return $post->post_title;
    }

    public function status_col( $post_id, $post ) {
        return get_post_meta( $post_id, 'status', true );
    }
}
