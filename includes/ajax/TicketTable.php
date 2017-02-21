<?php

namespace SmartcatSupport\ajax;

use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\TicketUtils;
use SmartcatSupport\util\UserUtils;

class TicketTable extends AjaxComponent {

    public function list_tickets() {
        $html = $this->render( $this->plugin->template_dir . '/ticket_table.php',
            array(
                'headers' => $this->table_headers(),
                'data' => $this->get_tickets()
            )
        );

        wp_send_json_success( $html );
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
                $agents = UserUtils::list_agents( array( '' => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ) );
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
                echo '<div class="actions">' .
                        '<button type="button" class="trigger icon-bubbles open-ticket"' .
                         'data-id="' . $ticket->ID . '"></button></div>';
                break;
        }
    }

    public function filter_tickets( $args ) {
        $form = include $this->plugin->config_dir . '/ticket_filter.php';

        if( $form->is_valid() ) {
            foreach( $form->data as $name => $value ) {
                if( !empty( $value ) ) {
                    $args['meta_query'][] = array( 'key' => $name, 'value' => $value );
                }
            }
        }

        return $args;
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_list_tickets' => array( 'list_tickets' ),
            'support_tickets_table_column_data' => array( 'table_data', 10, 2 ),
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
            'id'        => __( 'Case #', \SmartcatSupport\PLUGIN_ID ),
            'status'    => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
            'subject'   => __( 'Subject', \SmartcatSupport\PLUGIN_ID ),
            'email'     => __( 'Email', \SmartcatSupport\PLUGIN_ID ),
            'date'      => __( 'Date', \SmartcatSupport\PLUGIN_ID )
        );

        if( $this->plugin->edd_active || $this->plugin->woo_active ) {
            $headers['product'] = __( 'Product', \SmartcatSupport\PLUGIN_ID );
        }

        if( current_user_can( 'edit_others_tickets' ) ) {
            $headers['priority'] = __( 'Priority', \SmartcatSupport\PLUGIN_ID );
            $headers['agent'] = __( 'Assigned', \SmartcatSupport\PLUGIN_ID );
        }

        $headers['actions'] = __( 'Actions', \SmartcatSupport\PLUGIN_ID );

        return apply_filters( 'support_ticket_table_headers', $headers );
    }
}
