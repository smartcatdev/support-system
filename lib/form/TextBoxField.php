<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\TextBoxField' ) ) :
    /**
     * @deprecated
     */
class TextBoxField extends AbstractField {
    protected $type = 'text';

    public function __construct( array $args ) {
        parent::__construct( $args );

        $this->type = isset( $args['type'] ) ? $args['type'] : "text";
    }

    public function render() { ?>

        <input id="<?php echo $this->id; ?>"
               name="<?php echo $this->name; ?>"
               type="<?php echo $this->type; ?>"
               value="<?php echo $this->value; ?>"

            <?php $this->props(); ?>
            <?php $this->classes(); ?> />

    <?php }
}

endif;