<?php

namespace SmartcatSupport\ajax;

use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\form\ChoiceConstraint;
use smartcat\form\HiddenField;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use function SmartcatSupport\render_template;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class TicketHandler extends ActionListener {
    public function __construct() {
        $this->add_ajax_action( 'support_update_meta', 'update_meta_field' );
        $this->add_ajax_action( 'support_new_ticket', 'new_ticket' );
        $this->add_ajax_action( 'support_create_ticket', 'create_ticket' );
        $this->add_ajax_action( 'support_view_ticket', 'view_ticket' );
        $this->add_ajax_action( 'support_edit_ticket', 'edit_ticket' );
        $this->add_ajax_action( 'support_update_ticket', 'update_ticket' );
    }

    public function new_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            wp_send_json(
                render_template( 'ticket_create_modal', array(
                    'form' => self::configure_create_form()
                ) )
            );
        }
    }

    public function create_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            $form = self::configure_create_form();

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
        $ticket = $this->valid_request();

        if( current_user_can( 'edit_others_tickets' ) ) {
            wp_send_json(
                render_template( 'ticket_edit_modal', array(
                    'form' => self::configure_meta_form( $ticket )
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
                $form = self::configure_meta_form( $ticket );

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

    private static function configure_create_form() {
        $user = wp_get_current_user();
        $products = get_products();
        $form = new Form( 'create_ticket' );

        $form->add_field( new TextBoxField(
            array(
                'id'            => 'first_name',
                'value'         => $user->first_name,
                'label'         => __( 'First Name', TEXT_DOMAIN ),
                'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
                'constraints'   => array(
                    new RequiredConstraint()
                )
            )

        ) )->add_field( new TextBoxField(
            array(
                'id'            => 'last_name',
                'value'         => $user->last_name,
                'label'         => __( 'Last Name', TEXT_DOMAIN ),
                'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
                'constraints'   =>  array(
                    new RequiredConstraint()
                )
            )

        ) )->add_field( new TextBoxField(
            array(
                'id'            => 'email',
                'type'              => 'email',
                'value'             => $user->user_email,
                'label'             => __( 'Contact Email', TEXT_DOMAIN ),
                'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
                'sanitize_callback' => 'sanitize_email',
                'constraints'       => array(
                    new RequiredConstraint()
                )
            )

        ) );

        if( $products ) {
            $form->add_field( new SelectBoxField(
                array(
                    'id'            => 'product',
                    'label'         => __( 'Product', TEXT_DOMAIN ),
                    'error_msg'     => __( 'Please Select a product', TEXT_DOMAIN ),
                    'options'       => array( '' => __( 'Select a Product', TEXT_DOMAIN ) ) + $products,
                    'constraints'   => array(
                        new ChoiceConstraint( array_keys( $products ) )
                    )
                )

            ) );
        }

        $form->add_field( new TextBoxField(
            array(
                'id'            => 'subject',
                'label'         => __( 'Subject', TEXT_DOMAIN ),
                'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
                'constraints'   => array(
                    new RequiredConstraint()
                )
            )

        ) )->add_field( new TextBoxField(
            array(
                'id'            => 'subject',
                'label'         => __( 'Subject', TEXT_DOMAIN ),
                'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
                'constraints'   => array(
                    new RequiredConstraint()
                )
            )

        ) )->add_field( new TextAreaField(
            array(
                'id'            => 'content',
                'label'         => __( 'Description', TEXT_DOMAIN ),
                'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
                'constraints'   => array(
                    new RequiredConstraint()
                )
            )

        ) );

        return $form;
    }

    private static function configure_meta_form( $post ) {
        $agents     = get_agents();
        $statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
        $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

        $form = new Form( 'meta_form' );

        $form->add_field( new HiddenField(
            array(
                'id'    => 'id',
                'value' => $post->ID
            )

        ) )->add_field( new SelectBoxField(
            array(
                'id'          => 'agent',
                'error_msg'   => __( 'Invalid agent selected', TEXT_DOMAIN ),
                'label'       => __( 'Assigned To', TEXT_DOMAIN ),
                'options'     => array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + $agents,
                'value'       => get_post_meta( $post->ID, 'agent', true ),
                'constraints' => array(
                    new ChoiceConstraint( array_keys( $agents ) )
                )
            )

        ) )->add_field( new SelectBoxField(
            array(
                'id'          => 'status',
                'error_msg'   => __( 'Invalid status selected', TEXT_DOMAIN ),
                'label'       => __( 'Status', TEXT_DOMAIN ),
                'options'     => $statuses,
                'value'       => get_post_meta( $post->ID, 'status', true ),
                'constraints' => array(
                    new ChoiceConstraint( array_keys( $statuses ) )
                )
            )

        ) )->add_field( new SelectBoxField(
            array(
                'id'          => 'priority',
                'error_msg'   => __( 'Invalid priority selected', TEXT_DOMAIN ),
                'label'       => __( 'Priority', TEXT_DOMAIN ),
                'options'     => $priorities,
                'value'       => get_post_meta( $post->ID, 'priority', true ),
                'constraints' => array(
                    new ChoiceConstraint( array_keys( $priorities ) )
                )
            )

        ) );

        return $form;
    }
}
