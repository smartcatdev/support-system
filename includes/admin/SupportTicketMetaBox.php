<?php

namespace SmartcatSupport\admin;

use SmartcatSupport\template\TicketMetaFormBuilder;
use SmartcatSupport\util\View;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Metabox for support ticket information.
 * 
 * @since 1.0.0
 * @package admin
 * @author Eric Green <eric@smartcat.ca>
 */
class SupportTicketMetaBox extends MetaBox {
    
    /**
     * @var TicketMetaFormBuilder
     * @since 1.0.0
     */
    private $builder;

    /**
     * @var View
     */
    private $view;

    /**
     * @param View $view
     * @param TicketMetaFormBuilder $builder Configures the form for the metabox.
     *
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( View $view, TicketMetaFormBuilder $builder ) {
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

    private function setup_form( $post ) {
        return $this->builder->configure( $post );
    }
}
