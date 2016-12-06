<?php

namespace SmartcatSupport\form\field;

class CheckBox extends Field {
    private $cb_title;

    public function __construct( $id, array $args ) {
        parent::__construct( $id, $args );

        if( !empty( $args['cb_title'] ) ) {
            $this->cb_title = $args['cb_title'];
        }
    }

    public function render() { ?>

        <input data-field_name="<?php esc_attr_e( $this->id ); ?>"
               name="<?php esc_attr_e( $this->id ); ?>"

               <?php checked( $this->value ); ?>

               type="checkbox"
               class="form_field" />

        <span class="checkbox-title"><?php echo $this->cb_title; ?></span>

    <?php }
}
