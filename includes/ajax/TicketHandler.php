<?php

namespace SmartcatSupport\ajax;

use function SmartcatSupport\render_template;
use SmartcatSupport\util\ActionListener;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class TicketHandler extends ActionListener {
    public function __construct() {
//        $this->add_ajax_action( 'support_update_meta', 'update_meta_field' );
//        $this->add_ajax_action( 'support_new_ticket', 'new_ticket' );
//        $this->add_ajax_action( 'support_create_ticket', 'create_ticket' );
//        $this->add_ajax_action( 'support_view_ticket', 'view_ticket' );
//        $this->add_ajax_action( 'support_edit_ticket', 'edit_ticket' );
//        $this->add_ajax_action( 'support_update_ticket', 'update_ticket' );

        add_action( 'wp_ajax_support_update_meta', array( $this, 'update_meta_field' ) );
        add_action( 'wp_ajax_support_new_ticket', array( $this, 'new_ticket' ) );
        add_action( 'wp_ajax_support_create_ticket', array( $this, 'create_ticket' ) );
        add_action( 'wp_ajax_support_view_ticket', array( $this, 'view_ticket' ) );
        add_action( 'wp_ajax_support_edit_ticket', array( $this, 'edit_ticket' ) );
        add_action( 'wp_ajax_support_update_ticket', array( $this, 'update_ticket' ) );
    }

    public function new_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            wp_send_json(
                render_template( 'ticket_create_modal', array(
                    'form' => include SUPPORT_PATH . '/config/ticket_create_form.php'
                ) )
            );
        }
    }

    public function create_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            $form = include SUPPORT_PATH . '/config/ticket_create_form.php';

            if ( $form->is_valid() ) {
                $data = $form->data;

                $post_id = wp_insert_post( array(
                    'post_title'     => $data['subject'],
                    'post_content'   => $data['content'],
                    'post_status'    => 'publish',
                    'post_type'      => 'support_ticket',
                    'comment_status' => 'open'
                ) );

                if ( ! empty( $post_id ) ) {

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
        $post = $this->valid_request();

        if( !empty( $post ) ) {
            wp_send_json_success(
                render_template( 'ticket', array( 'post' => $post ) )
            );
        }
    }

    public function edit_ticket() {
        $post = $this->valid_request();

        if( current_user_can( 'edit_others_tickets' ) ) {
            wp_send_json(
                render_template( 'ticket_edit_modal', array(
                    'form' => include SUPPORT_PATH . '/config/ticket_meta_form.php'
                ) )
            );
        }
    }

    public function update_meta_field() {
        if( $this->valid_request() ) {
            update_post_meta( $_REQUEST['id'], $_REQUEST['meta'], $_REQUEST['value'] );
        }
    }

    public function update_ticket() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            if ( current_user_can( 'edit_others_tickets' ) ) {
                $form = include SUPPORT_PATH . '/config/ticket_meta_form.php';

                if ( $form->is_valid() ) {
                    $data = $form->data;

                    $post_id = wp_update_post( array(
                        'ID' => $data['id'],
                        'post_author' => null,
                        'post_date' => current_time( 'mysql' )
                    ) );

                    if ( !empty( $post_id ) ) {
                        foreach ( $data as $field => $value ) {
                            update_post_meta( $post_id, $field, $value );
                        }

                        update_post_meta( $post_id, '_edit_last', wp_get_current_user()->ID );

                        wp_send_json(
                            array(
                                'success' => true,
                                'id' => $post_id,
                                'data' => render_template( 'ticket', array( 'post' => $ticket ) )
                            )
                        );
                    }
                } else {
                    wp_send_json_error( $form->errors );
                }
            }
        }
    }

    private function valid_request() {
        $ticket = null;
        $user = wp_get_current_user();

        if( isset( $_REQUEST['id'] ) && (int) $_REQUEST['id'] > 0 ) {
            $post = get_post( $_REQUEST['id'] );

        if( isset( $post ) )
            if( $post->post_type == 'support_ticket' &&
                ( $post->post_author == $user->ID || user_can( $user->ID, 'edit_others_tickets' ) ) ) {
                $ticket = $post;
            }
        } else {
            $ticket = false;
        }

        return $ticket;
    }
}
