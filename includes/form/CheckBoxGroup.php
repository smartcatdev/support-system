<?php

namespace SmartcatSupport\form;

use smartcat\form\AbstractField;

class CheckBoxGroup extends AbstractField {

    protected $options = array();

    public function __construct( array  $args ) {
        parent::__construct( $args );

        $this->options = $args['options'];
        $this->class[] = 'form-check-input';
    }

    public  function render() { ?>

        <?php foreach( $this->options as $option => $label ) : ?>

            <div class="form-check">

                <label class="form-check-label"><input type="checkbox"
                    name="<?php echo $this->name; ?>[]"
                    value="<?php echo $option; ?>"

                    <?php $this->classes(); ?>
                    <?php $this->props(); ?>

                    <?php checked( $this->value[ $option ] ); ?> /> <?php echo $label; ?></label>

            </div>

        <?php endforeach; ?>

    <?php }
}