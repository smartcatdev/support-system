<?php

namespace SmartcatSupport\ajax;

use SmartcatSupport\util\ActionListener;
use SmartcatSupport\util\TemplateRender;

class TableHandler extends ActionListener {
    private $view;

    public function __construct( TemplateRender $view ) {
        $this->view = $view;

        $this->column_data_callbacks();
        $this->add_ajax_action( 'support_list_tickets', 'ticket_table' );
    }

    public function ticket_table() {
        wp_send_json( [
            'columns' => $this->datatables_columns( $this->table_headers() ),
            'html' => $this->view->render( 'table',
                [
                    'id'      => 'support_tickets_table',
                    'headers' => $this->table_headers(),
                    'data'    => $this->table_data()
                ]
            )
        ] );
    }

    private function table_data() {
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

    private function datatables_columns( array $headers ) {
        $columns = [];

        foreach( $headers as $key => $value ) {
            $columns[] = [ 'title' => $value, 'data' => $key ];
        }

        return $columns;
    }

    private function column_data_callbacks() {
        add_action( 'support_ticket_table_email_col', function ( $post_id ) {
            return get_post_meta( $post_id, 'email', true );
        } );

        add_action( 'support_ticket_table_status_col', function ( $post_id ) {
            return get_post_meta( $post_id, 'status', true );
        } );

        add_action( 'support_ticket_table_subject_col', function ( $post_id, $post ) {
            return $post->post_title;
        }, 10, 2 );
    }
}
