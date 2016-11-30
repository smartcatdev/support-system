<?php

namespace SmartcatSupport\admin;

use function SmartcatSupport\get_products;
use function SmartcatSupport\render_template;
use function SmartcatSupport\ticket_meta_form;
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

    public function __construct( FormBuilder $builder ) {
        parent::__construct(
            'ticket_meta',
            __( 'Ticket Information', TEXT_DOMAIN ),
            'support_ticket',
            'advanced',
            'high'
        );

        $this->builder = $builder;
    }
    
    /**
     * @see \SmartcatSupport\admin\MetaBox
     * @param WP_Post $post The current post.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function render( $post ) {
        $form = $this->configure_form( $post );

        echo render_template( 'metabox', array( 'form' => $form ) );
    }

    private function configure_form( $post ) {
        $agents = array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + get_agents();
        $statuses = get_option( Option::STATUSES, Option\Defaults::STATUSES );
        $priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );
        $products = get_products();

        if( $products ) {
            $this->builder->add( SelectBox::class, 'product', array(
                'label'         => __( 'Product', TEXT_DOMAIN ),
                'value'         => get_post_meta( $post->ID, 'product', true ),
                'options'       => array( '' => __( 'Select a Product', TEXT_DOMAIN ) ) + $products,
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
            'label'             => __( 'Assigned To', TEXT_DOMAIN ),
            'options'           => $agents,
            'value'             => get_post_meta( $post->ID, 'agent', true ),
            'constraints'       => array(
                $this->builder->create_constraint( Choice::class, array_keys( $agents ) )
            )

        ) )->add( SelectBox::class, 'status', array(
            'label'             => __( 'Status', TEXT_DOMAIN ),
            'options'           => $statuses,
            'value'             => get_post_meta( $post->ID, 'status', true ),
            'constraints'       => array(
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

        return $this->builder->get_form();
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
