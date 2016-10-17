<?php

namespace SmartcatSupport\form;

use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Description of SelectBox
 *
 * @author ericg
 */
class SelectBox extends Selection {
    public function render() {
        ?>
            <select id="<?php esc_attr_e( $this->id ); ?>"
                name="<?php esc_attr_e( $this->id ); ?>">
                
                <?php foreach( $this->options as $value => $label ) : ?>
                
                    <option value="<?php esc_attr_e( $value ); ?>"
                        <?php _e( $value == $this->default ? 'selected' : '' ); ?>>
                
                        <?php esc_html_e( __( $label, TEXT_DOMAIN ) ); ?>
                        
                    </option>
                         
                <?php endforeach; ?>
                
            </select>
        <?php
    }
}
