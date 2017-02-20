<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\TextAreaField' ) ) :

class TextAreaField extends AbstractField {

    public function render() { ?>

        <textarea id="<?php echo $this->id; ?>"
                  name="<?php echo $this->name; ?>"

                  <?php $this->props(); ?>
                  <?php $this->classes(); ?>><?php echo $this->value; ?></textarea>

        <?php }
}

endif;