<?php

namespace SmartcatSupport\form;


use smartcat\form\AbstractField;

class StaticField extends AbstractField {

    public function render() { ?>

        <span
            id="<?php esc_attr_e( $this->id ); ?>"
            class="form_field"><?php echo $this->value ?></span>

    <?php }
}
