<?php

namespace SmartcatSupport\form\field;

class TextArea extends Field {
    private $rows;
    private $cols;

    public function __construct( $id, array $args ) {
        parent::__construct( $id, $args );

        if( isset( $args['rows'] ) ) {
            $this->rows = $args['rows'];
        }

        if( isset( $args['cols'] ) ) {
            $this->cols = $args['cols'];
        }
    }

    public function render() { ?>

        <textarea data-field_name="<?php esc_attr_e( $this->id ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>"
            rows="<?php esc_attr_e( $this->rows ); ?>"
            cols="<?php esc_attr_e( $this->cols ); ?>"
            class="form_field"><?php esc_html_e( trim( $this->value ) ); ?></textarea>

        <?php }
}
