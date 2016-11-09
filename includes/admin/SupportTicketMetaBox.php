<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\util\View;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\constraint\Choice;
use SmartcatSupport\form\constraint\Date;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Metabox for support ticket information.
 * 
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
class SupportTicketMetaBox extends MetaBox {

    private $builder;
    private $view;

    public function __construct( View $view, FormBuilder $builder ) {
        parent::__construct( 'ticket_meta', __( 'Ticket Information', TEXT_DOMAIN ), 'support_ticket' ); 

        $this->builder = $builder;
        $this->view = $view;
    }
    
    /**
     * @see \SmartcatSupport\admin\MetaBox
     * @param WP_Post $post The current post.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function render( $post ) {
        $form = $this->setup_form( $post );

        echo $this->view->render( 'metabox', [ 'form' => $form ] );
    }

    private function setup_form( $post ) {

        $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
        $date = get_post_meta( $post->ID, 'date_opened', true );

        //<editor-fold desc="Form Configuration">
        $this->builder->add( TextBox::class, 'email',
            [
                'type'              => 'email',
                'label'             => 'Contact Email',
                'value'             => get_post_meta( $post->ID, 'email', true ),
                'sanitize_callback' => 'sanitize_email'
            ]
        )->add( SelectBox::class, 'agent',
            [
                'label'       => 'Assigned To',
                'options'     => $this->agents(),
                'value'       => $agent = get_post_meta( $post->ID, 'agent', true ),
                'constraints' => [
                    $this->builder->create_constraint( Choice::class, array_keys( $this->agents() ) )
                ]
            ]
        )->add( SelectBox::class, 'status',
            [
                'label'       => 'Status',
                'options'     => $statuses,
                'value'       => get_post_meta( $post->ID, 'status', true ),
                'constraints' => [
                    $this->builder->create_constraint( Choice::class, array_keys( $statuses ) )
                ]
            ]
        )->add( TextBox::class, 'date_opened',
            [
                'label'       => 'Date Opened',
                'type'        => 'date',
                'value'       => $date == '' ? date( 'Y-m-d' ) : $date,
                'constraints' => [
                    $this->builder->create_constraint( Date::class )
                ]
            ]
        );
        //</editor-fold>

        return apply_filters( 'support_ticket_metabox_form', $this->builder, $post )->get_form();
    }

    private function agents() {
        $agents[ '' ] = 'No Agent Assigned';

        $users = get_users( [ 'role' => [ 'support_agent' ] ] );

        if( $users != null ) {
            foreach( $users as $user ) {
                $agents[ $user->ID ] = $user->display_name;
            }
        }

        return $agents;
    }

    /**
     * @see \SmartcatSupport\admin\MetaBox
     * @param int $post_id The ID of the current post.
     * @param WP_Post $post The current post.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function save( $post_id, $post ) {
        $form = $this->setup_form( $post );

        if( $form->is_valid() ) {
            $data = $form->get_data();

            foreach( $data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
