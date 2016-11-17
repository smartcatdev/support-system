<?php

namespace SmartcatSupport\ajax;

use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\util\TemplateRender;
use const SmartcatSupport\TEXT_DOMAIN;

class TableHandler extends ActionListener {
    private $view;
    private $builder;

    public function __construct( TemplateRender $view ) {
        $this->view = $view;

        $this->column_data_callbacks();
        $this->add_ajax_action( 'support_list_tickets', 'ticket_table' );
    }

    public function ticket_table() {
        wp_send_json($this->view->render( 'tickets_table',
            [
                'headers' => $this->table_headers(),
                'data'    => $this->table_data()
            ]
        ));
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
            'subject'   => 'Subject',
            'email'     => 'Email',
            'status'    => 'Status'
        ];

        return apply_filters( 'support_ticket_table_headers', $headers );
    }

    private function column_data_callbacks() {
        add_action( 'support_ticket_table_email_col', function ( $post_id ) {
            return get_post_meta( $post_id, 'email', true );
        } );

        add_action( 'support_ticket_table_status_col', function ( $post_id ) {
            $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
            return $statuses[ get_post_meta( $post_id, 'status', true ) ];
        } );

        add_action( 'support_ticket_table_subject_col', function ( $post_id, $post ) {
            return $post->post_title;
        }, 10, 2 );
    }
}
