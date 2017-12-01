<?php

namespace ucare\ajax;

use ucare\Options;

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

                $data = array(
                    'post_title'     => $form->data['subject'],
                    'post_content'   => \ucare\util\encode_code_blocks( $form->data['description'] ),
                    'post_status'    => 'publish',
                    'post_type'      => 'support_ticket',
                    'comment_status' => 'open',
                    'meta_input'     => array(
                        'status'     => 'new',
                        'agent'      => 0,
                        '_edit_last' => get_current_user_id()
                    )
                );

                $category = $form->data['category'];

                // Remove them so that they are not saved as meta
                unset( $form->data['subject'] );
                unset( $form->data['category'] );
                unset( $form->data['description'] );

                // Add remaining keys as meta
                // TODO manually pull meta from $_POST
                foreach ( $form->data as $field => $value ) {
                    $data['meta_input'][ $field ] = $value;
                }


                if ( isset( $_REQUEST['override_author'] ) ) {

                    $user = get_user_by( 'id', absint( $_REQUEST['author'] ) );

                    if ( $user && in_array( $user, \ucare\get_users_with_cap() ) ) {
                        $data['post_author'] = $user->ID;
                    }

                }

                $post_id = wp_insert_post( $data );


                if ( is_numeric( $post_id ) ) {

                    if ( term_exists( $category, 'ticket_category' ) ) {
                        wp_set_post_terms( $post_id, $category, 'ticket_category' );
                    }


                    // link attachments with post
                    foreach ( json_decode( $_REQUEST['attachments'] ) as $attachment ) {

                        $attachment = array(
                            'ID'          => $attachment,
                            'post_parent' => $post_id
                        );

                        wp_update_post( $attachment );

                    }

//                    do_action( 'support_ticket_created', get_post( $post_id ) );

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
                array( 'ticket' => $ticket )
            );

            wp_send_json( array(
                'success' => true,
                'id'      => $ticket->ID,
                'title'   => $ticket->post_title,
                'content' => $html
            ) );
        }
    }

    /**
     * AJAX action for saving the ticket properties.
     *
     * @see config/ticket_properties_form.php
     * @since 1.0.0
     */
    public function update_ticket_properties() {
        if( current_user_can( 'manage_support_tickets' ) && isset( $_REQUEST['id'] ) ) {

            $ticket = $this->get_ticket( $_REQUEST['id'] );

            if ( !empty( $ticket ) ) {

                $form = include $this->plugin->config_dir . '/ticket_properties_form.php';

                if( $form->is_valid() ) {

                    foreach( $form->data as $field => $value ) {

                        update_post_meta( $ticket->ID, $field, $value, get_post_meta( $ticket->ID, $field, true ) );

                    }

                    update_post_meta( $ticket->ID, '_edit_last', wp_get_current_user()->ID );

                    do_action( 'support_ticket_updated', $ticket );

                    wp_send_json( array(
                        'ticket_id' => $ticket->ID,
                        'data'      => __( 'Ticket Successfully Updated', 'ucare' )
                    ) );

                }
            }
        }
    }

    public function close_ticket() {
        if( update_post_meta( $_POST['id'], 'status', 'closed' ) ) {
            wp_send_json_success( array( 'message' => __( 'Ticket successfully closed', 'ucare' ) ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Error closing ticket', 'ucare' ) ) );
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
        if( isset( $_GET['id'] ) ) {
            $ticket = $this->get_ticket( $_GET['id'] );

            if( !empty( $ticket ) ) {
                $html = $this->render($this->plugin->template_dir . '/sidebar.php',
                    array(
                        'ticket' => $ticket
                    )
                );

                wp_send_json_success( $html );
            }
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
                array( 'comments' => get_comments( array( 'post_id' => $ticket->ID, 'order' => 'ASC' ) ) )
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
        $ticket = $this->get_ticket( $_POST['id'], true );

        if ( !empty( $ticket ) && !empty( $_POST['content'] ) ) {
            $user   = wp_get_current_user();
            $status = get_post_meta( $ticket->ID, 'status', true );

            //TODO add error for flooding
            add_filter( 'comment_flood_filter', '__return_false' );

            $comment = wp_new_comment( array(
                'comment_post_ID'             => $ticket->ID,
                'comment_author'              => $user->display_name,
                'comment_author_email'        => $user->user_email,
                'comment_author_url'          => $user->user_url,
                'comment_content'             => \ucare\util\encode_code_blocks( trim( $_POST['content'] ) ),
                'comment_parent'              => 0,
                'comment_approved'            => 1,
                'user_id'                     => $user->ID
            ) );

            if ( !is_wp_error( $comment ) ) {

                do_action( 'support_ticket_reply', $comment, $ticket );

                $html = $this->render( $this->plugin->template_dir . '/comment.php', array( 'comment' => get_comment( $comment ) ) );

                wp_send_json(
                    array(
                        'success' => true,
                        'data'    => $html,
                        'ticket'  => $ticket->ID
                    ),
                    201
                );

            } else {
                wp_send_json_error( __( 'Reply cannot be blank', 'ucare' ), 400 );
            }
        } else {
            wp_send_json_error( null, 400 );
        }
    }

    public function list_tickets() {
        $html = $this->render( $this->plugin->template_dir . '/ticket_list.php',
            array( 'query' => $this->query_tickets() )
        );

        wp_send_json_success( $html );
    }

    public function filter_tickets( $query ) {

        $defaults = array(
            'id'       => 0,
            'category' => 0,
            'email'    => '',
            'agent'    => 0,
            'product'  => 0,
            'stale'    => false,
            'status'   => array(
                'new',
                'waiting',
                'opened',
                'responded',
                'needs_attention'
            )
        );

        $args   = array_merge( $defaults, $_GET );
        $search = $_GET['search'];

        unset( $args['search'] );

        if ( isset( $search ) ) {

            $query_pieces = explode( ':', $search );

            if ( count( $query_pieces ) > 1 ) {

                foreach( $query_pieces as $piece ) {

                    $q = explode( '=', $piece );

                    if ( !empty( $q[1] ) ) {
                        $args[ $q[0] ] = $q[1];
                    } else {
                        $args['search'] .= $q[0];
                    }

                }

            } else {

                $q = explode( '=', $query_pieces[0] );

                if ( count( $q ) > 1 ) {
                    $args[ $q[0] ] = $q[1];
                } else {
                    $args['search'] = $q[0];
                }

            }

        }

        unset( $args['action'] );
        unset( $args['page'] );
        unset( $args['_ajax_nonce'] );
        unset( $args['ticket_filter'] );


        if ( !current_user_can( 'manage_support_tickets' ) ) {

            // Restrict only to tickets created by the current user
            $query['author'] = wp_get_current_user()->ID;

        } else {

            $author = get_user_by( 'email', $args['email'] );

            if( $author ) {
                $query['author'] = $author->ID;
            }

            if ( $args['agent'] == -1 ) {

                $query['meta_query'][] = array(
                    'key'     => 'agent',
                    'value'   => 1,
                    'compare' => '<'
                );

            } else if ( $args['agent'] > 0 ) {

                $query['meta_query'][] = array(
                    'key'   => 'agent',
                    'value' => $args['agent']
                );

            }

        }

        unset( $args['agent'] );
        unset( $args['email'] );


        if ( !empty( $args['search'] ) ) {
            $query['s'] = $args['search'];
        }

        unset( $args['search'] );


        if ( !empty( $args['category'] ) ) {

            $query['tax_query'][] = array(
                'taxonomy' => 'ticket_category',
                'field'    => 'slug',
                'terms'    => array( $args['category'] )
            );

        }

        unset( $args['category'] );


        if ( !empty( $args['id'] ) ) {
            $query['p'] = $args['id'];
        }

        unset( $args['id'] );

        // Loop through the remaining args as meta fields
        foreach ( $args as $key => $value ) {

            if ( !empty( $value ) ) {

                $query['meta_query'][] = array(
                    'key'     => $key,
                    'value'   => $value
                );

            }

        }

        return $query;

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
            'wp_ajax_support_close_ticket' => array( 'close_ticket' ),

            'wp_ajax_support_list_comments' => array( 'list_comments' ),
            'wp_ajax_support_submit_comment' => array( 'submit_comment' ),
            'wp_ajax_support_list_tickets' => array( 'list_tickets' ),

            'support_ticket_list_query_vars' => array( 'filter_tickets' ),
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

        if( $strict && !current_user_can( 'manage_support_tickets' ) ) {
            $args['author'] = wp_get_current_user()->ID;
        }

        $query = new \WP_Query( $args );

        return $query->post;
    }

    private function query_tickets() {
        $args = array(
            'post_type'      => 'support_ticket',
            'post_status'    => 'publish',
            'posts_per_page' => get_option( Options::MAX_TICKETS, \ucare\Defaults::MAX_TICKETS ),
            'paged'          => isset ( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1
        );

        if ( !current_user_can( 'manage_support_tickets' ) ) {
            $args['author'] = wp_get_current_user()->ID;
        }

        return new \WP_Query( apply_filters( 'support_ticket_list_query_vars', $args ) );
    }
}
