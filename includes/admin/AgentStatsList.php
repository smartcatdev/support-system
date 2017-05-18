<?php

namespace SmartcatSupport\admin;

use smartcat\admin\ListTable;

class AgentStatsList extends ListTable {

    private $agents;
    private $start_date;
    private $end_date;

    public function __construct( $start_date, $end_date ) {
        parent::__construct( array(
            'singular' => __( 'Agent Total', \SmartcatSupport\PLUGIN_ID ),
            'plural'   => __( 'Agent Totals', \SmartcatSupport\PLUGIN_ID ),
            'ajax'     => false
        ) );

        $this->agents = \SmartcatSupport\util\list_agents();
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function get_columns() {
        return array(
            'uc_agent'          => __( 'Agent', \SmartcatSupport\PLUGIN_ID ),
            'uc_total_assigned' => __( 'Total Assigned', \SmartcatSupport\PLUGIN_ID ),
            'uc_total_closed'   => __( 'Closed', \SmartcatSupport\PLUGIN_ID ),
            'uc_percentage'        => __( 'Percentage', \SmartcatSupport\PLUGIN_ID )
        );
    }

    public function get_sortable_columns() {
        return array(
            'uc_agent'           => array( 'uc_agent', true ),
            'uc_total_assigned'  => array( 'uc_total_assigned', true ),
            'uc_total_closed'    => array( 'uc_total_closed', true ),
            'uc_percentage'      => array( 'uc_percentage', true )
        );
    }

    public function no_items() {
        _e( 'No totals available.', \ext_satisfaction\PLUGIN_ID );
    }

    public function column_default( $item, $column_name ) {
        $data = '—';

        switch( $column_name ) {
            case 'uc_agent':
                $data = $item['uc_agent'];
                break;

            case 'uc_percentage':
                if( !empty( $item['uc_total_closed'] ) && !empty( $item['uc_total_closed'] ) ) {
                    $data = number_format($item['uc_total_closed'] / $item['uc_total_assigned'] * 100, 1) . '%';
                }

                break;

            default:
                $data = $item[ $column_name ];
        }

        return $data;
    }

    public function prepare_items() {
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $per_page = 15;
        $data = $this->data();
        $current_page = $this->get_pagenum();
        $total_items = count( $data );

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ) );

        $this->items = $this->get_items( $data, $per_page, $current_page );
    }

    private function get_items( $data, $per_page = 5, $page_number = 1 ) {
        $offset = $offset = ( $page_number - 1 ) * $per_page;
        $data = array_slice( $data, $offset, $per_page );

//        if( !empty( $_REQUEST['orderby'] ) && $this->verify_nonce() ) {
//            $sort_col = array();
//
//            foreach( $data as $key => $row ) {
//                $sort_col[ $key ] = $row[ $_REQUEST['orderby'] ];
//            }
//
//            array_multisort( $sort_col, $_REQUEST['order'] == 'asc' ? SORT_ASC : SORT_DESC, $data );
//        }


        return $data;
    }

    private function data() {
        $data = array();
        $start = $this->start_date->format( 'Y-m-d h:i:s' );
        $end = $this->end_date->format( 'Y-m-d h:i:s' );

        foreach( $this->agents as $id => $name ) {
            $totals['uc_agent'] = $name;

            $totals['uc_total_assigned'] = (
                new \WP_Query(
                    array(
                        'post_type'   => 'support_ticket',
                        'post_status' => 'publish',
                        'date_query' => array(
                            array(
                                'after'       => $start,
                                'before'      => $end,
                            )
                        ),
                        'meta_query'  => array(
                            array(
                                'key'   => 'agent',
                                'value' => $id
                            ),
                        )
                    ) )
                )->post_count;

            $totals['uc_total_closed'] = (
                new \WP_Query(
                    array(
                        'post_type'   => 'support_ticket',
                        'post_status' => 'publish',
                        'meta_query'  => array(
                            array(
                                'key'   => 'agent',
                                'value' => $id
                            ),
                            array(
                                'key'   => 'closed_by',
                                'value' => $id
                            ),
                            array(
                                'key'     => 'closed_date',
                                'value'   => array( $start, $end ),
                                'compare' => 'BETWEEN'
                            ),
                        )
                    ) )
                )->post_count;

            $data[] = $totals;
        }

        return $data;
    }
}