<?php

namespace SmartcatSupport\admin;

use smartcat\post\AbstractMetaBox;
use function SmartcatSupport\render_template;

class MetaBox extends AbstractMetaBox {
    private $config;

    public function __construct( array $args ) {
        parent::__construct( $args );

        $this->config = $args['config'];
    }

    public function render( \WP_Post $post ) {
        echo render_template( 'metabox', array( 'form' => include $this->config ) );
    }

    public function save( $post_id, \WP_Post $post ) {
        $form = include $this->config;

        if( $form->is_valid() ) {
            $data = $form->data;

            foreach( $data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
