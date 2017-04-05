<?php

namespace smartcat\mail;

use smartcat\post\AbstractMetaBox;

class StyleMetabox extends AbstractMetaBox {

    public function render( \WP_Post $post ) { ?>
        <textarea rows="25" style="width: 100%" name="template_styles"><?php echo get_post_meta( $post->ID, 'styles', true ); ?></textarea>
    <? }

    public function save( $post_id, \WP_Post $post ) {
        if( isset( $_POST['template_styles'] ) ) {
            update_post_meta( $post_id, 'styles', wp_strip_all_tags( $_POST['template_styles'] ) );
        }
    }
}