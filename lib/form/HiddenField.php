<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\HiddenField' ) ) :
    /**
     * @deprecated
     */
class HiddenField extends AbstractField {
    
    public function render() { ?>

        <input id="<?php echo $this->id; ?>"
               name="<?php echo $this->name; ?>"
               value="<?php echo $this->value; ?>"
               type="hidden"

            <?php $this->props(); ?>
            <?php $this->classes(); ?> />

    <?php }
}

endif;