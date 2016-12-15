<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\HiddenField' ) ) :

class HiddenField extends Field {
    
    public function render() { ?>

        <input name="<?php esc_attr_e( $this->id ); ?>"
            value="<?php esc_attr_e( $this->value ); ?>"
            type="hidden"
            class="form_field" />

    <?php }
}

endif;