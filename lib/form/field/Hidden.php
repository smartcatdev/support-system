<?php

namespace SmartcatSupport\form\field;

/**
 * Description of Hidden
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class Hidden extends Field {
    
    public function render() { ?>

        <input data-field_name="<?php esc_attr_e( $this->id ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>"
            value="<?php esc_attr_e( $this->value ); ?>"
            type="hidden"
            class="form_field" />

    <?php }
}
