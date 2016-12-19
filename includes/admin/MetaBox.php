<?php

namespace SmartcatSupport\admin;

use smartcat\post\MetaBox as AbstractMetaBox;
use function SmartcatSupport\render_template;

class MetaBox extends AbstractMetaBox {
    private $config;

    public function __construct( array $args ) {
        parent::__construct( $args );

        $this->config = $args['config'];
    }

    public function render( $post ) {
        echo render_template( 'metabox', array( 'form' => include $this->config ) );
    }

    public function save( $post_id, $post ) {
        $form = include $this->config;

        if( $form->is_valid() ) {
            $data = $form->data;

            foreach( $data as $key => $value ) {
                update_post_meta( $post->ID, $key, $value );
            }
        }
    }
}
