<?php

namespace smartcat\form;

if( !class_exists( '\smarcat\form\SelectBoxField' ) ) :

class SelectBoxField extends AbstractField {
    private $options;

    public function __construct( array $args ) {
        parent::__construct( $args );
        
        $this->options = $args['options'];
    }
    
    public function render() { ?>

        <select name="<?php esc_attr_e( $this->id ); ?>"
            class="form_field">

            <?php foreach( $this->options as $value => $label ) : ?>
                
                <option value="<?php esc_attr_e( $value ); ?>"
                    <?php selected( $value, $this->value, true ); ?>>
                
                    <?php echo $label; ?>
                        
                </option>
                         
            <?php endforeach; ?>
                
        </select>

    <?php }
}

endif;