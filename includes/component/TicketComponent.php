<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\TemplateUtils;
use SmartcatSupport\util\TicketUtils;

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
                $post_id = wp_insert_post( array(
                    'post_title'     => $form->data['subject'],
                    'post_content'   => $form->data['content'],
                    'post_status'    => 'publish',
                    'post_type'      => 'support_ticket',
                    'comment_status' => 'open'
                ) );

                if( !empty( $post_id ) ) {

                    // Remove them so that they are not saved as meta
                    unset( $form->data['subject'] );
                    unset( $form->data['content'] );

                    foreach( $form->data as $field => $value ) {
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

    public function open_ticket() {
        $ticket = $this->get_ticket( $_REQUEST['id'] );

        if( !empty( $ticket ) ) {
            $status = get_post_meta( $ticket->ID, 'status', true );

            if( current_user_can( 'edit_others_tickets' ) && $status != 'closed' && $status != 'resolved' ) {
                update_post_meta( $ticket->ID, 'status', 'opened' );
            }

            wp_send_json(
                array(
                    'id' => $ticket->ID,
                    'title' => $ticket->post_title,
                    'content' => TemplateUtils::render_template(
                        $this->plugin->template_dir . '/ticket.php', array( 'ticket' => $ticket )
                    )
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
                $post_id = wp_update_post( array(
                    'ID'          => $form->data['id'],
                    'post_author' => $ticket->post_author,
                    'post_date'   => current_time( 'mysql' )
                ) );

                if( !empty( $post_id ) ) {
                    foreach( $form->data as $field => $value ) {
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

    public function notify_ticket_resolved( $null, $post_id, $key, $new ) {
        if( get_option( Option::NOTIFY_RESOLVED, Option\Defaults::NOTIFY_RESOLVED ) == 'on' ) {

            if( $key == 'status' && $new == 'resolved' ) {

                $ticket = get_post( $post_id );

                add_filter( 'parse_email_template', function( $content ) use ( $new, $ticket ) {
                    return str_replace( '{%subject%}', $ticket->post_title, $content );
                } );

                Mailer::send_template(
                    get_option( Option::RESOLVED_EMAIL_TEMPLATE ),
                    TicketUtils::ticket_author_email( $ticket )
                );
            }
        }
    }

    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_new_ticket' => array( 'new_ticket' ),
            'wp_ajax_support_create_ticket' => array( 'create_ticket' ),
            'wp_ajax_support_open_ticket' => array( 'open_ticket' ),
            'wp_ajax_support_edit_ticket' => array( 'edit_ticket' ),
            'wp_ajax_support_update_ticket' => array( 'update_ticket' ),
            'wp_ajax_support_update_meta' => array( 'update_meta_field' ),
            'update_post_metadata' => array( 'notify_ticket_resolved', 10, 4 )
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
