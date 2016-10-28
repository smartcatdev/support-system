<?php

namespace SmartcatSupport\form\field;

class TextArea extends Field {
    
    public function render() { ?>

        <textarea data-field_name="<?php esc_attr_e( $this->id ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>"
            class="form_field"><?php esc_html_e( trim( $this->value ) ); ?></textarea>

        <?php }
}
