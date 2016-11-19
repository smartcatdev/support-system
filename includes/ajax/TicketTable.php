<?php

namespace SmartcatSupport\ajax;

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\FormBuilder;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use function SmartcatSupport\render_template;
use const SmartcatSupport\TEXT_DOMAIN;
use SmartcatSupport\util\ActionListener;

class TicketTable extends ActionListener {
    private $builder;

    public function __construct( FormBuilder $builder ) {
        $this->builder = $builder;

        $this->column_data_callbacks();
        $this->add_ajax_action( 'support_list_tickets', 'ticket_table' );
        $this->add_ajax_action( 'support_refresh_tickets', 'refresh_tickets' );
    }

    public function ticket_table() {
        wp_send_json(
            render_template( 'tickets_overview', array(
                'headers' => $this->table_headers(),
                'data'    => $this->table_data( $this->build_query() ),
                'filter_form' => $this->configure_filter_form()
            ) )
        );
    }

    public function refresh_tickets() {
        $tickets = $this->build_query();

        wp_send_json_success(
            render_template( 'tickets_table', array(
                'headers' => $this->table_headers(),
                'data'    => $this->table_data( $tickets )
            ) )
        );
    }

    private function configure_filter_form() {
        $products = array( '' => __( 'Product', TEXT_DOMAIN ) ) + get_products();
        $agents = array( '' => __( 'Assigned', TEXT_DOMAIN ) ) + get_agents();
        $statuses = array( '' => __( 'Status', TEXT_DOMAIN ) ) + get_option( Option::STATUSES, Option\Defaults::STATUSES );

        if( $products ) {
            $this->builder->add( SelectBox::class, 'product', array(
                'options' => $products
            ) );
        }

        if( current_user_can( 'edit_others_tickets' ) ) {
            $this->builder->add( SelectBox::class, 'agent', array(
                'options' => $agents

            ) )->add( SelectBox::class, 'status', array(
                'options' => $statuses
            ) );
        }

        return $this->builder->get_form();
    }

    private function build_query() {
        $args = [
            'post_type' => 'support_ticket',
            'post_status' => 'publish'
        ];

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['author'] = wp_get_current_user()->ID;
        }

        $filter = $this->configure_filter_form();

        if( $filter->is_valid() ) {
            $data = $filter->get_data();

            foreach( $data as $param => $value ) {
                if( $value != '' ) {
                    $args['meta_query'][] = [ 'key' => $param, 'value' => $value ];
                }
            }
        }

        return new \WP_Query( $args );
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
