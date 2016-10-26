<?php

namespace SmartcatSupport\form\field;

class TextBox extends Field {
    private $type = 'text';
    
    public function __construct( $id, array $args = [] ) {
        parent::__construct( $id, $args );
        
        if( array_key_exists( 'type', $args ) ) {
            $this->type = $args['type'];
        }
    }
    
    public function sanitize( $value ) {
        switch( $this->type ) {
            case 'text':
                $value = sanitize_text_field( $value );
                break;
            
            case 'email':
                $value = sanitize_email( $value );
                break;
        }
        
        return $value;
    }

    public function render() { ?>

        <input type="<?php esc_attr_e( $this->type ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>"
            id="<?php esc_attr_e( $this->id ); ?>"
            value="<?php esc_attr_e( $this->value ); ?>" />

    <?php }
}
