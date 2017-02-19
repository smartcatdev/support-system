<?php

namespace SmartcatSupport\component;

use smartcat\core\AbstractComponent;
use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\TicketUtils;

class Tickets extends AbstractComponent {

    /**
     * AJAX action to launch the ticket creation screen.
     *
     * @since 1.0.0
     */
    public function new_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            wp_send_json( include_once $this->plugin->template_dir . '/ticket_create_modal.php' );
        }
    }

    /**
     * AJAX action for creating new tickets.
     *
     * @see config/ticket_create_form.php
     * @since 1.0.0
     */
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

                    wp_send_json_success( $post_id );
                }
            } else {
                wp_send_json_error( $form->errors );
            }
        }
    }

    /**
     * AJAX action for loading a ticket. If the ticket is new, sets the status to opened.
     *
     * @uses $_GET['id'] The ID of the ticket.
     * @since 1.0.0
     */
    public function load_ticket() {
        $ticket = $this->get_ticket( $_GET['id'] );

        if( !empty( $ticket ) ) {
            $status = get_post_meta( $ticket->ID, 'status', true );

            if( current_user_can( 'edit_others_tickets' ) && $status == 'new' ) {
                update_post_meta( $ticket->ID, 'status', 'opened' );
            }

            wp_send_json(
                array(
                    'success' => true,
                    'id' => $ticket->ID,
                    'title' => $ticket->post_title,
                    'content' => include_once $this->plugin->template_dir . '/ticket.php'
                )
            );
        }
    }

    /**
     * AJAX action for saving the ticket properties.
     *
     * @see config/ticket_properties_form.php
     * @since 1.0.0
     */
    public function update_ticket_properties() {
        if( current_user_can( 'edit_others_tickets' ) ) {
            $ticket = $this->get_ticket( $_POST['id'] );

            if ( !empty( $ticket ) ) {
                $form = include $this->plugin->config_dir . '/ticket_properties_form.php';

                if ( $form->is_valid() ) {
                    $post_id = wp_update_post( array(
                        'ID'          => $_REQUEST['id'],
                        'post_author' => $ticket->post_author,
                        'post_date'   => current_time( 'mysql' )
                    ) );

                    if ( is_int( $post_id ) ) {
                        foreach ( $form->data as $field => $value ) {
                            update_post_meta( $post_id, $field, $value );
                        }

                        update_post_meta( $post_id, '_edit_last', wp_get_current_user()->ID );
                        wp_send_json_success( $post_id );
                    }
                }
            }
        }
    }

    public function toggle_flag() {
        if( current_user_can( 'edit_others_tickets' ) ) {
            $flag = get_post_meta( $_POST['id'], 'flagged', true ) === 'on' ? '' : 'on';

            update_post_meta( $_POST['id'], 'flagged', $flag );
            wp_send_json_success( $flag );
        }
    }

    /**
     * AJAX action for loading the sidebar for a ticket.
     *
     * @users $_GET['id'] The ID of the ticket.
     * @since 1.0.0
     */
    public function sidebar() {
        $ticket = $this->get_ticket( $_GET['id'] );

        if( !empty( $ticket ) ) {
            wp_send_json_success( include_once $this->plugin->template_dir . '/sidebar.php' );
        }
    }

    /**
     * Sends an email to the user to notify them that their ticket has been marked as resolved.
     *
     * @param $null
     * @param $post_id
     * @param $key
     * @param $new
     * @since 1.0.0
     */
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

    /**
     * Hooks that the Component is subscribed to.
     *
     * @see \smartcat\core\AbstractComponent
     * @see \smartcat\core\HookSubscriber
     * @return array $hooks
     * @since 1.0.0
     */
    public function subscribed_hooks() {
        return array(
            'wp_ajax_support_new_ticket' => array( 'new_ticket' ),
            'wp_ajax_support_create_ticket' => array( 'create_ticket' ),
            'wp_ajax_support_load_ticket' => array( 'load_ticket' ),
            'wp_ajax_support_update_ticket' => array( 'update_ticket_properties' ),
            'wp_ajax_support_toggle_flag' => array( 'toggle_flag' ),
            'wp_ajax_support_ticket_sidebar' => array( 'sidebar' ),

            'update_post_metadata' => array( 'notify_ticket_resolved', 10, 4 )
        );
    }

    /**
     * Gets a ticket.
     *
     * @param int $id The ticket ID.
     * @param bool $strict Whether to restrict to the current user.
     * @return \WP_Post
     * @since 1.0.0
     */
    private function get_ticket( $id, $strict = false ) {
        $args = array( 'p' => $id, 'post_type' => 'support_ticket' );

        if( $strict || !current_user_can( 'edit_others_tickets' ) ) {
            $args['post_author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );

        return $query->post;
    }
}
