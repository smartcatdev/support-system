<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use SmartcatSupport\util\TemplateUtils;

class TicketComponent extends AbstractComponent {

    public function new_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            wp_send_json(
                TemplateUtils::render_template(
                    $this->plugin->template_dir . '/ticket_create_modal.php',
                    array(
                        'form' => include $this->plugin->config_dir . '/ticket_create_form.php'
                    )
                )
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
            wp_send_json_success(
                TemplateUtils::render_template(
                    $this->plugin->template_dir . '/ticket.php',
                    array( 'post' => $ticket )
                )
            );
        }
    }

    public function edit_ticket() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );
        $form = include $this->plugin->config_dir . '/ticket_meta_form.php';

        wp_send_json(
            TemplateUtils::render_template(
                $this->plugin->template_dir . '/ticket_edit_modal.php',
                array( 'form' => $form )
            )
        );
    }

    public function update_ticket() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        if( !empty( $ticket ) ) {
            $form = include $this->plugin->dir() . '/config/ticket_meta_form.php';

            if( $form->is_valid() ) {
                $data = $form->data;

                $post_id = wp_update_post( array(
                    'ID'          => $data['id'],
                    'post_author' => null,
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
                                $this->plugin->dir() . '/template-parts/ticket.php',
                                array( 'post' => $ticket )
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

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_new_ticket' => array( 'new_ticket' ),
            'wp_ajax_support_create_ticket' => array( 'create_ticket' ),
            'wp_ajax_support_view_ticket' => array( 'view_ticket' ),
            'wp_ajax_support_edit_ticket' => array( 'edit_ticket' ),
            'wp_ajax_support_update_ticket' => array( 'update_ticket' ),
            'wp_ajax_support_update_meta' => array( 'update_meta_field' ),
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