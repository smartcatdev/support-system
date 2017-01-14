<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\util\TemplateUtils;
use SmartcatSupport\util\UserUtils;

class TicketComponent extends AbstractComponent {

    public function new_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            wp_send_json(
                TemplateUtils::render_template( $this->plugin->template_dir . '/ticket_create_modal.php' )
            );
        }
    }

    public function create_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            $form = include $this->plugin->config_dir . '/ticket_create_form.php';

            if ( $form->is_valid() ) {
                $data = $form->data;

                $post_id = wp_insert_post( array(
                    'post_title'     => $data['subject'],
                    'post_content'   => $data['content'],
                    'post_status'    => 'publish',
                    'post_type'      => 'support_ticket',
                    'comment_status' => 'open'
                ) );

                if( !empty( $post_id ) ) {

                    // Remove them so that they are not saved as meta
                    unset( $data['subject'] );
                    unset( $data['content'] );

                    foreach ( $data as $field => $value ) {
                        update_post_meta( $post_id, $field, $value );
                    }

                    update_post_meta( $post_id, 'status', 'new' );
                    update_post_meta( $post_id, '_edit_last', wp_get_current_user()->ID );

                    wp_send_json_success();
                }
            } else {
                wp_send_json_error( $form->errors );
            }
        }
    }

    public function view_ticket() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        if( !empty( $ticket ) ) {
            if( get_post_meta( $ticket->ID, 'status', true ) == 'new' && current_user_can( 'edit_others_tickets' ) ) {
                update_post_meta( $ticket->ID, 'status', 'viewed' );
            }

            wp_send_json_success(
                TemplateUtils::render_template(
                    $this->plugin->template_dir . '/ticket.php', array( 'ticket' => $ticket )
                )
            );
        }
    }

    public function edit_ticket() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        wp_send_json(
            TemplateUtils::render_template(
                $this->plugin->template_dir . '/ticket_edit_modal.php', array( 'ticket' => $ticket )
            )
        );
    }

    public function update_ticket() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        if( !empty( $ticket ) ) {
            $form = include $this->plugin->config_dir . '/ticket_meta_form.php';

            if( $form->is_valid() ) {
                $data = $form->data;

                $post_id = wp_update_post( array(
                    'ID'          => $data['id'],
                    'post_author' => $ticket->post_author,
                    'post_date'   => current_time( 'mysql' )
                ) );

                if( !empty( $post_id ) ) {
                    foreach( $data as $field => $value ) {
                        update_post_meta( $post_id, $field, $value );
                    }

                    update_post_meta( $post_id, '_edit_last', wp_get_current_user()->ID );

                    wp_send_json(
                        array(
                            'success' => true,
                            'id'      => $post_id,
                            'data'    => TemplateUtils::render_template(
                                $this->plugin->template_dir . '/ticket.php', array( 'ticket' => $ticket )
                            )
                        )
                    );
                }
            } else {
                wp_send_json_error( $form->errors );
            }
        }
    }

    public function update_meta_field() {
        if( !empty( $this->get_ticket( $_REQUEST['id'] ) ) ) {
            update_post_meta( $_REQUEST['id'], $_REQUEST['meta'], $_REQUEST['value'] );
        }
    }

    public function notify_status_change( $null, $obj_id, $key, $new ) {
        if( get_option( Option::NOTIFY_STATUS, Option\Defaults::NOTIFY_STATUS ) == 'on' ) {

            $previous = get_post_meta( $obj_id, 'status', true );
            $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );

            if( $key == 'status' && !empty( $new ) && $previous != $new ) {

                add_filter( 'parse_email_template', function( $content ) use ( $new, $statuses ) {
                    return str_replace( '{%status%}', $statuses[ $new ], $content );
                } );

                Mailer::send_template(
                    get_option( Option::STATUS_EMAIL_TEMPLATE ),
                    get_post_meta( $obj_id, 'email', true )
                );
            }
        }
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_new_ticket' => array( 'new_ticket' ),
            'wp_ajax_support_create_ticket' => array( 'create_ticket' ),
            'wp_ajax_support_view_ticket' => array( 'view_ticket' ),
            'wp_ajax_support_edit_ticket' => array( 'edit_ticket' ),
            'wp_ajax_support_update_ticket' => array( 'update_ticket' ),
            'wp_ajax_support_update_meta' => array( 'update_meta_field' ),
            'update_post_metadata' => array( 'notify_status_change', 10, 4 )
        );
    }

    private function get_ticket( $id ) {
        $args = array( 'p' => $id, 'post_type' => 'support_ticket' );

        if( !current_user_can( 'edit_others_tickets' ) ) {
            $args['post_author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );

        return $query->post;
    }
}
