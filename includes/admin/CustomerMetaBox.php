<?php

namespace SmartcatSupport\admin;

use smartcat\form\Form;
use smartcat\form\TextBoxField;
use smartcat\post\MetaBox;
use function SmartcatSupport\render_template;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Metabox for support ticket information.
 * 
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
class CustomerMetaBox extends MetaBox {

    public function __construct() {
        parent::__construct(
            'ticket_customer_meta',
            __( 'Customer Information', TEXT_DOMAIN ),
            'support_ticket',
            'advanced',
            'high'
        );
    }
    
    /**
     * @see \SmartcatSupport\admin\MetaBox
     * @param WP_Post $post The current post.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function render( $post ) {
        echo render_template( 'metabox', array( 'form' => self::configure_form( $post ) ) );
    }

    private static function configure_form( $post ) {
        $form = new Form( 'customer_meta_box' );

        $form->add_field( new TextBoxField(
            array(
                'id'                => 'email',
                'type'              => 'email',
                'label'             => __( 'Contact Email', TEXT_DOMAIN ),
                'value'             => get_post_meta( $post->ID, 'email', true ),
                'sanitize_callback' => 'sanitize_email'
            )
        ) )->add_field( new TextBoxField(
            array(
                'id'    => 'website_url',
                'type'              => 'url',
                'label'             => __( 'Website', TEXT_DOMAIN ),
                'value'             => get_post_meta( $post->ID, 'website_url', true )
            )
        ) );

        return $form;
    }

    /**
     * @see \SmartcatSupport\admin\MetaBox
     * @param int $post_id The ID of the current post.
     * @param WP_Post $post The current post.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function save( $post_id, $post ) {
        $form = self::configure_form( $post );

        if( $form->is_valid() ) {
            $data = $form->data;

            foreach( $data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
