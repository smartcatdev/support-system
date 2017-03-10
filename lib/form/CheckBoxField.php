<?php

namespace smartcat\form;

if( !class_exists( 'smartcat\form\CheckBoxField' ) ) :

class CheckBoxField extends AbstractField {

    public function render() { ?>

        <input id="<?php echo $this->id; ?>"
               name="<?php echo $this->name; ?>"
               type="checkbox"

            <?php checked( $this->value ); ?>
            <?php $this->props(); ?>
            <?php $this->classes(); ?> />

    <?php }
}

endif;