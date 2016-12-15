<?php

namespace smartcat\form;

if( !class_exists( '\smarcat\form\SelectBoxField' ) ) :

class SelectBoxField extends Field {
    private $options;

    public function __construct( $id, array $args ) {
        parent::__construct( $id, $args );
        
        $this->options = $args['options'];
    }
    
    public function render() { ?>

        <select name="<?php esc_attr_e( $this->id ); ?>"
            class="form_field <?php echo esc_attr_e( $this->class ); ?>"

            <?php foreach( $this->data_attrs as $attr => $value ) : ?>

                data-<?php echo $attr; ?>="<?php esc_attr_e( $value ); ?>"

            <?php endforeach; ?> >


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