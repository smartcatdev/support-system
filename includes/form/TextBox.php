<?php

namespace SmartcatSupport\form;

class TextBox extends Field {
    
    public function render() { 
        ?>
            <input type="email"
                   name="<?php esc_attr_e( $this->id ) ?>"
                   id="<?php esc_attr_e( $this->id ) ?>" />
        <?php 
    }
}
