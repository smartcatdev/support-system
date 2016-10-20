<?php

namespace SmartcatSupport\form;

/**
 * Description of Hidden
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class Hidden extends Field {
    
    public function render() {
        ?>
            <input id="<?php esc_attr_e( $this->id ); ?>"
                name="<?php esc_attr_e( $this->id ); ?>"
                value="<?php esc_attr_e( $this->value ); ?>"
                type="hidden" />
        <?php     
    }
}
