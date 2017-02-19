<?php

namespace smartcat\form;

if( !class_exists( 'smartcat\form\CheckBoxField' ) ) :

class CheckBoxField extends AbstractField {
    public $cb_title;

    public function __construct( array $args ) {
        parent::__construct( $args );

        if( !empty( $args['cb_title'] ) ) {
            $this->cb_title = $args['cb_title'];
        }
    }

    public function render() { ?>

        <input name="<?php esc_attr_e( $this->id ); ?>"

            <?php checked( $this->value ); ?>

            type="checkbox"

            <?php $this->classes() ?> />

        <span class="checkbox-title"><?php echo $this->cb_title; ?></span>

    <?php }
}

endif;