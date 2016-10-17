<?php

namespace SmartcatSupport\form;

/**
 * Description of Email
 *
 * @author ericg
 */
class Email extends Field {

    public function render() {
        ?>
            <input type="email"
                name="<?php esc_attr_e( $this->id ); ?>"
                id="<?php esc_attr_e( $this->id ); ?>" 
                value="<?php esc_attr_e( $this->default ); ?>" />
        <?php 
    }
    
    public function validate( $value ) {
        return sanitize_email( $value );
    }
}
