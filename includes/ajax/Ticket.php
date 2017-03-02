<?php

namespace SmartcatSupport\ajax;

use smartcat\mail\Mailer;
use SmartcatSupport\descriptor\Option;

class Ticket extends AjaxComponent {

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
                    'post_content'   => $form->data['description'],
                    'post_status'    => 'publish',
                    'post_type'      => 'support_ticket',
                    'comment_status' => 'open'
                ) );

                if( !empty( $post_id ) ) {

                    // Remove them so that they are not saved as meta
                    unset( $form->data['subject'] );
                    unset( $form->data['description'] );

                    foreach( $form->data as $field => $value ) {
                        update_post_meta( $post_id, $field, $value );
                    }

                    update_post_meta( $post_id, 'status', 'new' );
                    update_post_meta( $post_id, '_edit_last', wp_get_current_user()->ID );

                    wp_send_json_success( $post_id );
                }
            } else {
                wp_send_json_error( $form->errors, 400 );
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

            if( current_user_can( 'manage_support_tickets' ) && $status == 'new' ) {
                update_post_meta( $ticket->ID, 'status', 'opened' );
            }

            $html = $this->render( $this->plugin->template_dir . '/ticket.php',
                array(
                    'ticket' => $ticket
                )
            );

            wp_send_json(
                array(
                    'success' => true,
                    'id' => $ticket->ID,
                    'title' => $ticket->post_title,
                    'content' => $html
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
        if( current_user_can( 'manage_support_tickets' ) ) {
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
                        wp_send_json(
                            array(
                                'ticket_id' => $post_id,
                                'data' => __( 'Ticket Successfully Updated', \SmartcatSupport\PLUGIN_ID )
                            )
                        );
                    }
                }
            }
        }
    }

    public function toggle_flag() {
        if( current_user_can( 'manage_support_tickets' ) ) {
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
            $html = $this->render(  $this->plugin->template_dir . '/sidebar.php',
                array(
                    'ticket' => $ticket
                )
            );

            wp_send_json_success( $html );
        }
    }

    /**
     * AJAX action to retrieve all comments for a ticket. Returns an array of rendered comments.
     *
     * @uses $_GET['id'] The ID of the ticket to retrieve comments for.
     * @since 1.0.0
     */
    public function list_comments() {
        $ticket = $this->get_ticket( $_GET['id'] );

        if( !empty( $ticket ) ) {

            $html = $this->render( $this->plugin->template_dir . '/comments.php',
                array(
                    'comments' => get_comments( array( 'post_id' => $ticket->ID, 'order' => 'ASC' ) )
                )
            );

            wp_send_json_success( $html );
        }
    }

    /**
     * AJAX action for submitting new tickets. Ensure user has proper privileges and then emails
     * the comments content. If the user is an agent, sets the status to waiting, else sets the
     * status to responded.
     *
     * @uses $_POST['id'] The id of the ticket to comment on.
     * @uses $_POST['content'] The content of the comment.
     * @since 1.0.0
     */
    public function submit_comment() {
        $ticket = $this->get_ticket( $_POST['id'] );

        if ( !empty( $ticket ) && !empty( $_POST['content'] ) ) {
            $user   = wp_get_current_user();
            $status = get_post_meta( $ticket->ID, 'status', true );

            //TODO add error for flooding
            add_filter( 'comment_flood_filter', '__return_false' );

            $comment = wp_handle_comment_submission( array(
                'comment_post_ID'             => $ticket->ID,
                'author'                      => $user->display_name,
                'email'                       => $user->user_email,
                'url'                         => $user->user_url,
                'comment'                     => $_POST['content'],
                'comment_parent'              => 0,
                'user_id'                     => $user->ID,
                '_wp_unfiltered_html_comment' => '_wp_unfiltered_html_comment'
            ) );

            if ( !is_wp_error( $comment ) ) {
                if ( current_user_can( 'manage_support_tickets' ) ) {
                    update_post_meta( $ticket->ID, 'status', 'waiting' );

                    // Grab email template vars
                    add_filter( 'parse_email_template', function ( $content ) use ( $comment, $ticket ) {
                        return str_replace(
                            array( '{%agent%}', '{%reply%}', '{%subject%}' ),
                            array( $comment->comment_author, $comment->comment_content, $ticket->post_title ),
                            $content
                        );
                    } );

                    Mailer::send_template( get_option( Option::REPLY_EMAIL_TEMPLATE ), get_post_meta( $ticket->ID, 'email', true ) );
                } elseif ( $status != 'new' ) {
                    update_post_meta( $ticket->ID, 'status', 'responded' );
                }

                $html = $this->render( $this->plugin->template_dir . '/comment.php',
                    array(
                        'comment' => $comment
                    )
                );

                wp_send_json(
                    array(
                        'success' => true,
                        'data'    => $html,
                        'ticket'  => $ticket->ID
                    ), 201 );
            } else {
                wp_send_json_error( __( 'Reply cannot be blank', \SmartcatSupport\PLUGIN_ID ), 400 );
            }
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
                    \SmartcatSupport\util\ticket\author_email( $ticket )
                );
            }
        }
    }

    public function list_tickets() {
        $html = $this->render( $this->plugin->template_dir . '/ticket_list.php',
            array(
                'query' => $this->query_tickets()
            )
        );

        wp_send_json_success( $html );
    }

    public function filter_tickets( $args ) {
        $form = include $this->plugin->config_dir . '/ticket_filter.php';

        $args['s'] = isset( $_REQUEST['search'] ) ? $_REQUEST['search'] : '';

        if( $form->is_valid() ) {
            unset( $form->data['search'] );

            foreach( $form->data as $name => $value ) {
                if( !empty( $value ) ) {
                    $args['meta_query'][] = array( 'key' => $name, 'value' => $value );
                }
            }
        }

        return $args;
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
        return parent::subscribed_hooks( array(
            'wp_ajax_support_create_ticket' => array( 'create_ticket' ),
            'wp_ajax_support_load_ticket' => array( 'load_ticket' ),
            'wp_ajax_support_update_ticket' => array( 'update_ticket_properties' ),
            'wp_ajax_support_toggle_flag' => array( 'toggle_flag' ),
            'wp_ajax_support_ticket_sidebar' => array( 'sidebar' ),

            'wp_ajax_support_list_comments' => array( 'list_comments' ),
            'wp_ajax_support_submit_comment' => array( 'submit_comment' ),
            'wp_ajax_support_list_tickets' => array( 'list_tickets' ),

            'support_ticket_table_query_vars' => array( 'filter_tickets' ),
            'update_post_metadata' => array( 'notify_ticket_resolved', 10, 4 )
        ) );
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

        if( $strict || !current_user_can( 'manage_support_tickets' ) ) {
            $args['post_author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );

        return $query->post;
    }

    private function query_tickets() {
        $args = array(
            'post_type'      => 'support_ticket',
            'post_status'    => 'publish',
            'posts_per_page' => get_option( Option::MAX_TICKETS, Option\Defaults::MAX_TICKETS ),
            'paged'          => isset ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1
        );

        if ( ! current_user_can( 'manage_support_tickets' ) ) {
            $args['author'] = wp_get_current_user()->ID;
        }

        return new \WP_Query( apply_filters( 'support_ticket_table_query_vars', $args ) );
    }
}
