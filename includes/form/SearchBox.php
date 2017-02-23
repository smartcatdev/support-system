<?php

namespace SmartcatSupport\form;


use smartcat\form\TextBoxField;

class SearchBox extends TextBoxField {
    public function render() { ?>

        <span class="input-group">
            <input id="<?php echo $this->id; ?>"
               name="<?php echo $this->name; ?>"
               type="<?php echo $this->type; ?>"
               value="<?php echo $this->value; ?>"

            <?php $this->props(); ?>
            <?php $this->classes(); ?> />
            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
        </span>

    <?php }
}