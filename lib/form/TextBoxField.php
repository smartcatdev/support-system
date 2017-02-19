<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\TextBoxField' ) ) :

class TextBoxField extends AbstractField {
    private $type = 'text';
    
    public function __construct( array $args ) {
        parent::__construct( $args );
        
        if( !empty( $args['type'] ) ) {
            $this->type = $args['type'];
        }
    }

    public function render() { ?>

        <input type="<?php esc_attr_e( $this->type ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>"
            value="<?php esc_attr_e( $this->value ); ?>"
            <?php $this->classes(); ?> />

    <?php }
}

endif;