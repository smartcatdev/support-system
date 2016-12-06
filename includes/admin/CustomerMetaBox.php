<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MetaBox;
use function SmartcatSupport\render_template;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\form\field\TextBox;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Metabox for support ticket information.
 * 
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
class CustomerMetaBox extends MetaBox {
    private $builder;

    public function __construct( FormBuilder $builder ) {
        parent::__construct(
            'ticket_customer_meta',
            __( 'Customer Information', TEXT_DOMAIN ),
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

        echo render_template( 'metabox', array( 'form' => $form, 'post' => $post ) );
    }

    private function configure_form( $post ) {

        $this->builder->add( TextBox::class, 'email', array(
            'type'              => 'email',
            'label'             => __( 'Contact Email', TEXT_DOMAIN ),
            'value'             => get_post_meta( $post->ID, 'email', true ),
            'sanitize_callback' => 'sanitize_email'

        ) )->add( TextBox::class, 'website_url', array(
            'type'              => 'url',
            'label'             => __( 'Website', TEXT_DOMAIN ),
            'value'             => get_post_meta( $post->ID, 'website_url', true )
        ));


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
