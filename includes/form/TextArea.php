<?php

namespace SmartcatSupport\form;

class TextArea extends Field {
    
    public function render() {
        $value = trim( esc_html( $this->value ) ); ?>

            <textarea id="<?php esc_attr_e( $this->id ); ?>"
                name="<?php esc_attr_e( $this->id ); ?>"><?php _e( $value ) ?></textarea>
        <?php
    }
}
