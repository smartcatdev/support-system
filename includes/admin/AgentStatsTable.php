<?php

namespace ucare\admin;

use smartcat\admin\ListTable;

class AgentStatsTable extends ListTable {

    private $agents;
    private $start_date;
    private $end_date;

    public function __construct( $start_date, $end_date ) {
        parent::__construct( array(
            'singular' => __( 'Agent Total', \ucare\PLUGIN_ID ),
            'plural'   => __( 'Agent Totals', \ucare\PLUGIN_ID ),
            'ajax'     => false
        ) );

        $this->agents = \ucare\util\list_agents();
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function get_columns() {
        return array(
            'uc_agent'            => __( 'Agent', \ucare\PLUGIN_ID ),
            'uc_number_assigned'  => __( 'Assigned', \ucare\PLUGIN_ID ),
            'uc_number_closed'    => __( 'Closed', \ucare\PLUGIN_ID ),
            'uc_workload_percent' => __( '% Workload', \ucare\PLUGIN_ID )
        );
    }

    public function get_sortable_columns() {
        return array(
            'uc_agent'            => array( 'uc_agent', true ),
            'uc_number_assigned'  => array( 'uc_total_assigned', true ),
            'uc_number_closed'    => array( 'uc_total_closed', true ),
            'uc_workload_percent' => array( 'uc_workload_percent', true )
        );
    }

    public function no_items() {
        _e( 'No totals available.', \ucare\PLUGIN_ID );
    }

    public function extra_tablenav( $which ) {

        if( $which == 'top' ) { ?>

            <div class="alignleft actions filteractions">
                <select name="agent">
                    <option value="0"><?php _e( 'All Agents', \ucare\PLUGIN_ID ); ?></option>

                    <?php foreach( $this->agents as $id => $name ) : ?>

                        <option value="<?php echo $id; ?>"

                            <?php selected( isset( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : '', $id ); ?>>

                            <?php echo $name; ?>

                        </option>

                    <?php endforeach; ?>

                </select>
                <input type="submit" name="filter_action" class="button" value="<?php _e( 'Filter', \ucare\PLUGIN_ID ); ?>">
            </div>

        <?php }
    }

    public function column_default( $item, $column_name ) {
        $data = '—';

        switch( $column_name ) {
            case 'uc_agent':
                $data = $item['uc_agent'];
                break;

            case 'uc_workload_percent':
                $data = number_format( $item['uc_workload_percent'], 1 ) . '%';
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

        $sort_col = array();

        foreach( $data as $key => $row ) {
            $sort_col[ $key ] = $row[ isset( $_REQUEST['orderby'] ) ? $_REQUEST['orderby'] : 'uc_workload_percent' ];
        }

        array_multisort( $sort_col, $_REQUEST['order'] == 'asc' ? SORT_ASC : SORT_DESC, $data );

        return $data;
    }

    private function data() {
        $agents_data = array();
        $total_assigned = 0;

        foreach( $this->agents as $id => $name ) {
            $totals = $this->get_agent_totals( $id );
            $totals['uc_agent'] = $name;
            $agents_data[ $id ] = $totals;
            $total_assigned += $totals['uc_number_assigned'];
        }

        // Closure for calculating the totals
        $calc_workload = function( $id ) use ( $total_assigned, $agents_data ) {
            return $agents_data[ $id ]['uc_number_assigned'] / $total_assigned * 100;
        };

        if( !empty( $_GET['agent'] ) && $this->verify_nonce() ) {

            if( isset( $agents_data[ $_GET['agent'] ] ) ) {
                $agents_data[ $_GET['agent'] ]['uc_workload_percent'] = $calc_workload( $_GET['agent'] );

                // Return array containing only currently selected agent
                return array( $agents_data[ $_REQUEST['agent'] ] );
            }

        } else {

            foreach( $this->agents as $id => $name ) {
                $agents_data[ $id ]['uc_workload_percent'] = $calc_workload( $id );
            }

            return array_values( $agents_data );

        }
    }

    private function get_agent_totals( $id ) {
        $start = $this->start_date->format( 'Y-m-d 00:00:00' );
        $end = $this->end_date->format( 'Y-m-d 23:59:59' );

        $totals['uc_number_assigned'] = (
            new \WP_Query(
                array(
                    'posts_per_page' => -1,
                    'post_type'      => 'support_ticket',
                    'post_status'    => 'publish',
                    'date_query'     => array(
                        'after'  => $start,
                        'before' => $end
                    ),
                    'meta_query'      => array(
                        array(
                            'key'   => 'agent',
                            'value' => $id
                        ),
                    )
                ) )
            )->post_count;

        $totals['uc_number_closed'] = (
            new \WP_Query(
                array(
                    'posts_per_page' => -1,
                    'post_type'      => 'support_ticket',
                    'post_status'    => 'publish',
                    'meta_query'     => array(
                        array(
                            'key'   => 'agent',
                            'value' => $id
                        ),
                        array(
                            'key'   => 'status',
                            'value' => 'closed'
                        ),
                        array(
                            'key'     => 'closed_date',
                            'type'    => 'DATE',
                            'value'   => array( $start, $end ),
                            'compare' => 'BETWEEN'
                        ),
                    )
                ) )
            )->post_count;

        return $totals;
    }
}
