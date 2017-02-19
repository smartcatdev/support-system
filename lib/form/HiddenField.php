<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\HiddenAbstractField' ) ) :

class HiddenField extends AbstractField {
    
    public function render() { ?>

        <input name="<?php esc_attr_e( $this->id ); ?>"
            value="<?php esc_attr_e( $this->value ); ?>"
            type="hidden"
            <?php $this->classes(); ?> />

    <?php }
}

endif;