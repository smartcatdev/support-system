<?php

namespace SmartcatSupport\ajax;

use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\util\TemplateRender;
use const SmartcatSupport\TEXT_DOMAIN;

class TableHandler extends ActionListener {
    private $view;
    private $builder;

    public function __construct( TemplateRender $view, FormBuilder $builder ) {
        $this->view = $view;
        $this->builder = $builder;

        $this->column_data_callbacks();
        $this->add_ajax_action( 'support_list_tickets', 'ticket_table' );
        $this->add_ajax_action( 'support_refresh_tickets', 'refresh_table' );
    }

    public function ticket_table() {
        wp_send_json(
            $this->view->render( 'tickets_overview', [
                'headers' => $this->table_headers(),
                'data'    => $this->table_data( $this->build_query() ),
                'form'    => $this->configure_filter_form()
            ]
        ) );
    }

    public function refresh_table() {
        if( current_user_can( 'edit_tickets' ) ) {
            $form = $this->configure_filter_form();
            $data = [];

            if( $form->is_valid() ) {
                $data = $form->get_data();
            }

            $tickets = $this->build_query( $data );

            wp_send_json_success(
                $this->view->render( 'tickets_table', [
                    'headers' => $this->table_headers(),
                    'data'    => $this->table_data( $tickets )
                ]
            ) );
        }
    }

    private function build_query( array $meta_args = [] ) {
        $args = [
            'post_type' => 'support_ticket',
            'post_status' => 'publish'
        ];

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['author'] = wp_get_current_user()->ID;
        }

        foreach( $meta_args as $param => $value ) {
            if( $value != '' ) {
                $args['meta_query'][] = [ 'key' => $param, 'value' => $value ];
            }
        }

        return new \WP_Query( $args );
    }

    private function configure_filter_form() {
        $agents = [ '' => __( 'Assigned', TEXT_DOMAIN ) ] + support_system_agents();
        $statuses = [ '' => __( 'Status', TEXT_DOMAIN ) ] + get_option( Option::STATUSES, Option\Defaults::STATUSES );

        $this->builder->add( SelectBox::class, 'status',
            [
                'options'     => $statuses,
                'value'       => '',
                'constraints' => [
                    $this->builder->create_constraint( Choice::class, array_keys( $statuses ) )
                ]
            ]
        );

        if( current_user_can( 'edit_others_tickets' ) ) {

            $this->builder->add( SelectBox::class, 'agent',
                [
                    'options'     => $agents,
                    'value'       => '',
                    'constraints' => [
                        $this->builder->create_constraint( Choice::class, array_keys( $agents ) )
                    ]
                ]
            );

        }

        return $this->builder->get_form();
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

    private function table_data( \WP_Query $query ) {
        $rows = [];

        while( $query->have_posts() ) {
            $query->the_post();

            $data = [];

            foreach( array_keys( $this->table_headers() ) as $col ) {
                $data[ $col ] = apply_filters( "support_ticket_table_{$col}_col", $query->post->ID, $query->post );
            }

            $rows[] = $data;

        }

        wp_reset_postdata();

        return $rows;
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
