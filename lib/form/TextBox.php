<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\TextBox' ) ) :

class TextBox extends Field {
    private $type = 'text';
    
    public function __construct( $id, array $args = array() ) {
        parent::__construct( $id, $args );
        
        if( array_key_exists( 'type', $args ) ) {
            $this->type = $args['type'];
        }
    }

    public function render() { ?>

        <input type="<?php esc_attr_e( $this->type ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>"
            data-field_name="<?php esc_attr_e( $this->id ); ?>"
            value="<?php esc_attr_e( $this->value ); ?>"
            class="form_field" />

    <?php }
}

endif;