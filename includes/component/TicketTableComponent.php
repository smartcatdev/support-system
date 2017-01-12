<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\util\TemplateUtils;
use SmartcatSupport\util\UserUtils;

class TicketTableComponent extends AbstractComponent {

    public function list_tickets() {
        wp_send_json(
            TemplateUtils::render_template(
                $this->plugin->template_dir . '/ticket_table.php',
                array(
                    'headers' => $this->table_headers(),
                    'data' => $this->get_tickets()
                )
            )
        );
    }

    public function table_data( $col, $ticket ) {
        switch( $col ) {
            case 'id':
                echo $ticket->ID;
                break;

            case 'subject':
                echo $ticket->post_title;
                break;

            case 'email':
                echo get_post_meta( $ticket->ID, 'email', true );
                break;

            case 'status':
                $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
                $status = get_post_meta( $ticket->ID, 'status', true );

                if( array_key_exists( $status, $statuses ) ) {
                    echo $statuses[ $status ];
                }

                break;


            case 'priority':
                $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );
                $priority = get_post_meta( $ticket->ID, 'priority', true );

                if( array_key_exists( $priority, $priorities ) ) {
                    echo $priorities[ $priority ];
                }

                break;

            case 'date':
                echo get_the_date( 'M j Y g:i A', $ticket->ID );
                break;

            case 'agent':
                $agents = UserUtils::list_agents( array( '' => __( 'Unassigned', PLUGIN_ID ) ) );
                $agent = get_post_meta( $ticket->ID, 'agent', true );

                if( array_key_exists( $agent ,$agents ) ) {
                    echo $agents[ $agent ];
                }

                break;


            case 'product':
                $products = apply_filters( 'support_list_products', array() );
                $product = get_post_meta( $ticket->ID, 'product', true );

                if( array_key_exists( $product, $products ) ) {
                    echo $products[ $product ];
                }

                break;

            case 'actions':
                echo '<div class="actions">';

                if( current_user_can( 'edit_others_tickets' ) ) {
                    echo '<a style="display: table; width: 100%;" href="' . admin_url( 'admin-ajax.php' ) . '?action=support_edit_ticket&id=' . $ticket->ID . '" ' .
                         'rel="modal:open"><i class="left icon-pencil"></i></a>';
                }

                echo '<i class="right trigger icon-bubbles" data-action="view_ticket"></i></div>';
                break;
        }
    }

    public function filter_fields() {
        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $products = new SelectBoxField(
                array(
                    'id'      => 'product',
                    'options' => apply_filters( 'support_list_products', array( '' => __( 'All Products', PLUGIN_ID ) ) ),
                    'value'   => isset( $_REQUEST['product'] ) ? $_REQUEST['product'] : ''
                )
            );

            $products->render();
        }

        if( current_user_can( 'edit_others_tickets' ) ) {
            $agents = new SelectBoxField(
                array(
                    'id'      => 'agent',
                    'options' => UserUtils::list_agents( array( '' => __( 'All Agents', PLUGIN_ID ) ) ),
                    'value'   => isset( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

            $agents->render();
        }

        $statuses = new SelectBoxField(
            array(
                'id'      => 'status',
                'options' => array( '' => __( 'Any Status', PLUGIN_ID ) ) + get_option( Option::STATUSES, Option\Defaults::STATUSES ),
                'value'   => isset( $_REQUEST['status'] ) ? $_REQUEST['status'] : ''
            )
        );

        $statuses->render();
    }

    public function filter_tickets( $args ) {
        if( !empty( $_REQUEST['product'] ) ) {
            $args['meta_query'][] = array( 'key' => 'product', 'value' => $_REQUEST['product'] );
        }

        if( !empty( $_REQUEST['agent'] ) ) {
            $args['meta_query'][] = array( 'key' => 'agent', 'value' => $_REQUEST['agent'] );
        }

        if( !empty( $_REQUEST['status'] ) ) {
            $args['meta_query'][] = array( 'key' => 'status', 'value' => $_REQUEST['status'] );
        }

        return $args;
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_list_tickets' => array( 'list_tickets' ),
            'support_tickets_table_column_data' => array( 'table_data', 10, 2 ),
            'support_tickets_table_filters' => array( 'filter_fields' ),
            'support_ticket_table_query_vars' => array( 'filter_tickets' )
        );
    }

    private function get_tickets() {
        $args = array(
            'post_type' => 'support_ticket',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( apply_filters( 'support_ticket_table_query_vars', $args ) );

        return $query->posts;
    }

    private function table_headers() {
        $headers = array(
            'id'        => __( 'Case #', PLUGIN_ID ),
            'subject'   => __( 'Subject', PLUGIN_ID ),
            'email'     => __( 'Email', PLUGIN_ID ),
            'status'    => __( 'Status', PLUGIN_ID ),
            'date'      => __( 'Date', PLUGIN_ID )
        );

        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $headers['product'] = __( 'Product', PLUGIN_ID );
        }

        if( current_user_can( 'edit_others_tickets' ) ) {
            $headers['priority'] = __( 'Priority', PLUGIN_ID );
            $headers['agent'] = __( 'Assigned', PLUGIN_ID );
        }

        $headers['actions'] = __( 'Actions', PLUGIN_ID );

        return apply_filters( 'support_ticket_table_headers', $headers );
    }
}
