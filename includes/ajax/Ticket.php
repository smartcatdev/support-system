<?php

namespace SmartcatSupport\ajax;

use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use function SmartcatSupport\render_template;
use function SmartcatSupport\ticket_meta_form;
use SmartcatSupport\util\TemplateRender;
use SmartcatSupport\util\ActionListener;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\field\TextArea;
use SmartcatSupport\form\field\Hidden;
use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\constraint\Required;
use const SmartcatSupport\TEXT_DOMAIN;
use SmartcatSupport\descriptor\Strings;

/**
 * Description of SingleTicket
 *
 * @author ericg
 */
class Ticket extends ActionListener {
    private $builder;

    public function __construct( FormBuilder $builder ) {
        $this->builder = $builder;

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
                    'form' => $this->configure_create_form()
                ) )
            );
        }
    }

    public function create_ticket() {
        if( current_user_can( 'create_support_tickets' ) ) {
            $form = $this->configure_create_form();

            if ( $form->is_valid() ) {
                $data = $form->get_data();

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
                wp_send_json_error( $form->get_errors() );
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
                    'form' => $this->configure_meta_form( $ticket )
                ) )
            );
        }
    }

    public function update_ticket() {
        $ticket = $this->valid_request();

        if( !empty( $ticket ) ) {
            if ( current_user_can( 'edit_others_tickets' ) ) {
                $form = $this->configure_meta_form( $ticket );

                if ( $form->is_valid() ) {
                    $data = $form->get_data();

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
                    wp_send_json_error( $form->get_errors() );
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

    private function configure_create_form() {
        $this->builder->clear_config();
        $user = wp_get_current_user();

        $products = get_products();

        $this->builder->add( TextBox::class, 'first_name', array(
            'value'             => $user->first_name,
            'label'             => __( 'First Name', TEXT_DOMAIN ),
            'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
            'constraints'       => array(
                $this->builder->create_constraint( Required::class )
            )

        ) )->add( TextBox::class, 'last_name', array(
            'value'             => $user->last_name,
            'label'             => __( 'Last Name', TEXT_DOMAIN ),
            'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
            'constraints'       =>  array(
                $this->builder->create_constraint( Required::class )
            )

        ) )->add( TextBox::class, 'email', array(
            'type'              => 'email',
            'value'             => $user->user_email,
            'label'             => __( 'Contact Email', TEXT_DOMAIN ),
            'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
            'sanitize_callback' => 'sanitize_email',
            'constraints'       => array(
                $this->builder->create_constraint( Required::class )
            )

        ) );

        if( $products ) {
            $this->builder->add( SelectBox::class, 'product', array(
                'label'         => __( 'Product', TEXT_DOMAIN ),
                'error_msg'     => __( 'Please Select a product', TEXT_DOMAIN ),
                'options'       => $products + array( '' => __( 'Select a Product', TEXT_DOMAIN ) ),
                'constraints'   => array(
                    $this->builder->create_constraint( Choice::class, array_keys( $products ) )
                )
            ) );
        }

        $this->builder->add( TextBox::class, 'subject', array(
            'label'         => __( 'Subject', TEXT_DOMAIN ),
            'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
            'constraints'   => array(
                $this->builder->create_constraint( Required::class )
            )

        ) )->add( TextArea::class, 'content', array(
            'label'         => __( 'Description', TEXT_DOMAIN ),
            'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
            'constraints'   => array(
                $this->builder->create_constraint( Required::class )
            )
        ) );

        return $this->builder->get_form();
    }

    private function configure_meta_form( $post ) {
        $this->builder->clear_config();

        if( current_user_can( 'edit_others_tickets' ) ) {
            $agents = get_agents();
            $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
            $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

            $this->builder->add( Hidden::class, 'id', array(
                'value'       => $post->ID

            ) )->add( SelectBox::class, 'agent', array(
                'error_msg'   => __( 'Invalid agent selected', TEXT_DOMAIN ),
                'label'       => __( 'Assigned To', TEXT_DOMAIN ),
                'options'     => $agents,
                'value'       => get_post_meta( $post->ID, 'agent', true ),
                'constraints' => array(
                    $this->builder->create_constraint( Choice::class, array_keys( $agents ) )
                )

            ) )->add( SelectBox::class, 'status', array(
                'error_msg'   => __( 'Invalid status selected', TEXT_DOMAIN ),
                'label'       => __( 'Status', TEXT_DOMAIN ),
                'options'     => $statuses,
                'value'       => get_post_meta( $post->ID, 'status', true ),
                'constraints' => array(
                    $this->builder->create_constraint( Choice::class, array_keys( $statuses ) )
                )
            ) )->add( SelectBox::class, 'priority', array(
                'error_msg'   => __( 'Invalid priority selected', TEXT_DOMAIN ),
                'label'       => __( 'Priority', TEXT_DOMAIN ),
                'options'     => $priorities,
                'value'       => get_post_meta( $post->ID, 'priority', true ),
                'constraints' => array(
                    $this->builder->create_constraint( Choice::class, array_keys( $priorities ) )
                )
            ) );
        }

        return $this->builder->get_form();
    }
}
