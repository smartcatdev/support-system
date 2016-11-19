<?php

namespace SmartcatSupport\admin;

use function SmartcatSupport\get_products;
use SmartcatSupport\util\TemplateRender;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\form\field\TextBox;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\constraint\Choice;
use function SmartcatSupport\get_agents;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Metabox for support ticket information.
 * 
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
class SupportMetaBox extends MetaBox {

    private $builder;
    private $view;

    public function __construct( TemplateRender $view, FormBuilder $builder ) {
        parent::__construct(
            'ticket_meta',
            __( 'Ticket Information', TEXT_DOMAIN ),
            'support_ticket',
            'advanced',
            'high'
        );

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
        $form = $this->configure_form( $post );

        echo $this->view->render( 'metabox', array( 'form' => $form ) );
    }

    private function configure_form( $post ) {
        $agents = array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + get_agents();

        $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );

        $products = get_products();

        if( $products ) {
            $this->builder->add( SelectBox::class, 'product', array(
                'label'         => __( 'Product', TEXT_DOMAIN ),
                'value'         => get_post_meta( $post->ID, 'product', true ),
                'options'       => $products + array( '' => __( 'Select a Product', TEXT_DOMAIN ) ),
                'constraints'   => array(
                    $this->builder->create_constraint( Choice::class, array_keys( $products ) )
                )
            ) );
        }

        $this->builder->add( TextBox::class, 'email', array(
            'type'              => 'email',
            'label'             => __( 'Contact Email', TEXT_DOMAIN ),
            'value'             => get_post_meta( $post->ID, 'email', true ),
            'sanitize_callback' => 'sanitize_email'

        ) )->add( SelectBox::class, 'agent', array(
            'label'       => __( 'Assigned To', TEXT_DOMAIN ),
            'options'     => $agents,
            'value'       => get_post_meta( $post->ID, 'agent', true ),
            'constraints' => array(
                $this->builder->create_constraint( Choice::class, array_keys( $agents ) )
            )

        ) )->add( SelectBox::class, 'status', array(
            'label'       => __( 'Status', TEXT_DOMAIN ),
            'options'     => $statuses,
            'value'       => get_post_meta( $post->ID, 'status', true ),
            'constraints' => array(
                $this->builder->create_constraint( Choice::class, array_keys( $statuses ) )
            )
        ) );

        return apply_filters( 'support_ticket_metabox_form', $this->builder, $post )->get_form();
    }

    /**
     * @see \SmartcatSupport\admin\MetaBox
     * @param int $post_id The ID of the current post.
     * @param WP_Post $post The current post.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function save( $post_id, $post ) {
        $form = $this->configure_form( $post );

        if( $form->is_valid() ) {
            $data = $form->get_data();

            foreach( $data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
