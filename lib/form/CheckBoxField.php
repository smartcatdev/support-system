<?php

namespace smartcat\form;

if( !class_exists( 'smartcat\form\CheckBoxField' ) ) :

class CheckBoxField extends AbstractField {

    protected $checkbox_label = '';

    public function __construct( array $args ) {
        parent::__construct( $args );

        $this->checkbox_label = $args['checkbox_label'];
    }

    public function render() { ?>

        <label>

            <input id="<?php echo $this->id; ?>"
                   name="<?php echo $this->name; ?>"
                   type="checkbox"

                <?php checked( $this->value ); ?>
                <?php $this->props(); ?>
                <?php $this->classes(); ?> /> <?php echo $this->checkbox_label; ?>

        </label>

    <?php }
}

endif;