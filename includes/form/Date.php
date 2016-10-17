<?php

namespace SmartcatSupport\form;

/**
 * Description of Date
 *
 * @author ericg
 */
class Date extends Field {
    public function render() {
        ?> 
            <input type="date"
                id="<?php esc_attr_e( $this->id ); ?>"
                name="<?php esc_attr_e( $this->id ); ?>"
                value="<?php esc_attr_e( $this->default ); ?>" />
        <?php
    }
    
    public function validate( $value ) {
        $date = date_create( $value );
        
        if( $date === false ) {
            $value = $this->default;
        } else {
            $value = $date->format( 'Y-m-d' );
        }

        return $value;
    }
}
