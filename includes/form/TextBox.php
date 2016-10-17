<?php

namespace SmartcatSupport\form;

class TextBox extends Field {
    
    public function render() {   
        ?>
            <input type="text"
                   name="<?php esc_attr_e( $this->id ); ?>"
                   id="<?php esc_attr_e( $this->id ); ?>" 
                   value="<?php esc_attr_e( $this->default ); ?>" />
        <?php 
    }
    
    public function validate( $value ) {
        return sanitize_text_field( $value );
    }
}
