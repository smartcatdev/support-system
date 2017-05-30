<?php

namespace ucare\admin;


use smartcat\admin\ListTable;

class LogsTable extends ListTable {

    private $classes;

    public function __construct() {
        parent::__construct( array(
            'singular' => __( 'Log', \ucare\PLUGIN_ID ),
            'plural'   => __( 'Logs', \ucare\PLUGIN_ID ),
            'ajax'     => false
        ) );

        $this->classes = array(
            'i' => __( 'Info', \ucare\PLUGIN_ID ),
            'd' => __( 'Debug', \ucare\PLUGIN_ID ),
            'e' => __( 'Error', \ucare\PLUGIN_ID ),
            'w' => __( 'Warning', \ucare\PLUGIN_ID ),
        );

        apply_filters( 'support_default_log_classes', $this->classes );
    }

    public function get_columns() {
        return array(
            'uc_log_class'        => __( 'Level', \ucare\PLUGIN_ID ),
            'uc_log_type'         => __( 'Event', \ucare\PLUGIN_ID ),
            'uc_log_message'      => __( 'Message', \ucare\PLUGIN_ID ),
            'uc_log_timestamp'    => __( 'Timestamp', \ucare\PLUGIN_ID )
        );
    }

    public function get_sortable_columns() {
        return array(
            'uc_log_class'        => array( 'uc_log_class', true ),
            'uc_log_type'         => array( 'uc_log_type', true ),
            'uc_log_timestamp'    => array( 'uc_log_timestamp', true )
        );
    }

    public function no_items() {
        _e( 'No logs available.', \ucare\PLUGIN_ID );
    }

    public function extra_tablenav( $which ) {

        if( $which == 'top' ) { ?>

            <div class="alignleft actions filteractions">

                <select name="level">
                    <option value=""><?php _e( 'Verbose', \ucare\PLUGIN_ID ); ?></option>

                    <?php foreach( $this->classes as $class => $name ) : ?>

                        <option value="<?php echo $class; ?>"

                            <?php selected( isset( $_REQUEST['level'] ) ? $_REQUEST['level'] : '', $class ); ?>>

                            <?php echo $name; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <select name="type">
                    <option value=""><?php _e( 'All Types', \ucare\PLUGIN_ID ); ?></option>

                    <?php $types = $this->get_log_types(); ?>

                    <?php foreach( $types as $type ) : ?>

                        <option value="<?php echo $type; ?>"

                            <?php selected( isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : '', $type ); ?>>

                            <?php echo ucwords( strtolower( $type ) ); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <input type="submit" name="filter_action" class="button" value="<?php _e( 'Filter', \ucare\PLUGIN_ID ); ?>">

                <button class="button" name="clear"><span style="line-height: 26px" class="dashicons dashicons-trash"></span></button>

            </div>

        <?php }
    }


    public function column_uc_log_class( $item ) {
        if( array_key_exists( $item['uc_log_class'], $this->classes ) ) {
            return $this->classes[ $item['uc_log_class'] ];
        } else {
            return $item['uc_log_class'];
        }
    }

    public function column_uc_log_message( $item ) {
        return wp_trim_words( $item['uc_log_message'], 15 );
    }

    public function column_default( $item, $column_name ) {
        if( !empty( $item[ $column_name ] ) ) {
            return $item[ $column_name ];
        } else {
            return '—';
        }
    }

    public function prepare_items() {
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $per_page = 15;
        $current_page = $this->get_pagenum();
        $total_items = $this->record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ) );

        $this->items = $this->get_logs( $per_page, $current_page );
    }

    private function record_count() {
        global $wpdb;

        return $wpdb->query( "SELECT COUNT(*) FROM {$wpdb->prefix}ucare_logs" );
    }

    private function get_logs( $per_page = 5, $page_number = 1 ) {
        global $wpdb;

        $offset = $offset = ( $page_number - 1 ) * $per_page;
        $vars = array();

        $q = "SELECT class AS uc_log_class,
                type AS uc_log_type,
                timestamp AS uc_log_timestamp,
                message AS uc_log_message
              FROM {$wpdb->prefix}ucare_logs";

        if( $this->verify_nonce() && isset( $_REQUEST['filter_action'] ) ) {

            $and = false;

            if( !empty( $_GET['level'] ) ) {
                $q .= ' WHERE class = %s ';
                $vars[] = $_GET['level'];
                $and = true;
            }

            if( !empty( $_GET['type'] ) ) {
                $q .=  ( $and ? ' AND ' : ' WHERE ' ). ' type = %s ';
                $vars[] = $_GET['type'];
            }

        }

        $q .= ' ORDER BY ' . ( !empty( $_REQUEST['orderby'] ) ? esc_sql( $_REQUEST['orderby'] ) : 'uc_log_timestamp' ) . ' ';

        $q .= isset( $_REQUEST['order'] ) ? esc_sql( $_REQUEST['order'] ) : ' DESC ';

        $q .= ' LIMIT %d OFFSET %d';
        $vars[] = $per_page;
        $vars[] = $offset;

        $q = $wpdb->prepare( $q, $vars );

        return $wpdb->get_results( $q, ARRAY_A );
    }

    private function get_log_types() {
        global $wpdb;

        return $wpdb->get_col( "SELECT DISTINCT type FROM {$wpdb->prefix}ucare_logs", 0 );
    }

}