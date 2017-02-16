<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TemplateUtils;
use SmartcatSupport\util\TicketUtils;
use SmartcatSupport\util\UserUtils;

class TicketTableComponent extends AbstractComponent {

    public function list_tickets() {
        wp_send_json_success(
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
                echo TicketUtils::ticket_author_email( $ticket );
                break;

            case 'status':
                $statuses = get_option( Option::STATUSES, Option\Defaults::$STATUSES );
                $status = get_post_meta( $ticket->ID, 'status', true );

                if( array_key_exists( $status, $statuses ) ) {
                    echo '<span class="status-wrapper">'
                            . '<span class="ticket-status ' . $status . '"></span>'
                            . '<span class="status-tooltip">' . $statuses[ $status ] . '</span>'
                            . '</span>';
                }

                break;


            case 'priority':
                $priorities = get_option( Option::PRIORITIES, Option\Defaults::$PRIORITIES );
                $priority = get_post_meta( $ticket->ID, 'priority', true );

                if( array_key_exists( $priority, $priorities ) ) {
                    echo $priorities[ $priority ];
                }

                break;

            case 'date':
                echo get_the_date( 'M j Y g:i A', $ticket->ID );
                break;

            case 'agent':
                $agents = UserUtils::list_agents( array( '' => __( 'Unassigned', Plugin::ID ) ) );
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
                    echo '<a href="' . admin_url( 'admin-ajax.php' ) . '?action=support_edit_ticket&id=' . $ticket->ID . '" ' .
                         'rel="modal:open" class="action-icon"><i class="left icon-pencil"></i></a>';
                }

                echo '<button type="button" class="right trigger icon-bubbles open-ticket"' .
                         'data-action="view_ticket"' .
                         'data-id="' . $ticket->ID . '"></button></div>';
                break;
        }
    }

    public function filter_fields() {
        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $products = new SelectBoxField(
                array(
                    'id'      => 'product',
                    'options' => apply_filters( 'support_list_products', array( '' => __( 'All Products', Plugin::ID ) ) ),
                    'value'   => isset( $_REQUEST['product'] ) ? $_REQUEST['product'] : ''
                )
            );

            $products->render();
        }

        if( current_user_can( 'edit_others_tickets' ) ) {
            $agents = new SelectBoxField(
                array(
                    'id'      => 'agent',
                    'options' => UserUtils::list_agents( array( '' => __( 'All Agents', Plugin::ID ) ) ),
                    'value'   => isset( $_REQUEST['agent'] ) ? $_REQUEST['agent'] : ''
                )
            );

            $agents->render();
        }

        $statuses = new SelectBoxField(
            array(
                'id'      => 'status',
                'options' => array( '' => __( 'Any Status', Plugin::ID ) ) + get_option( Option::STATUSES, Option\Defaults::$STATUSES ),
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
            'id'        => __( 'Case #', Plugin::ID ),
            'status'    => __( 'Status', Plugin::ID ),
            'subject'   => __( 'Subject', Plugin::ID ),
            'email'     => __( 'Email', Plugin::ID ),
            'date'      => __( 'Date', Plugin::ID )
        );

        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $headers['product'] = __( 'Product', Plugin::ID );
        }

        if( current_user_can( 'edit_others_tickets' ) ) {
            $headers['priority'] = __( 'Priority', Plugin::ID );
            $headers['agent'] = __( 'Assigned', Plugin::ID );
        }

        $headers['actions'] = __( 'Actions', Plugin::ID );

        return apply_filters( 'support_ticket_table_headers', $headers );
    }
}
