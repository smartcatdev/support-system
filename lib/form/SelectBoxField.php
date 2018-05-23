<?php

namespace smartcat\form;

if( !class_exists( '\smarcat\form\SelectBoxField' ) ) :
    /**
     * @deprecated
     */
class SelectBoxField extends AbstractField {
    private $options;

    public function __construct( array $args ) {
        parent::__construct( $args );
        
        $this->options = $args['options'];
    }
    
    public function render() { ?>

        <select id="<?php echo $this->id; ?>"
                name="<?php echo $this->name; ?>"

            <?php $this->props(); ?>
            <?php $this->classes(); ?>>

            <?php foreach( $this->options as $value => $label ) : ?>
                
                <option value="<?php echo $value; ?>"
                    <?php selected( $value, $this->value, true ); ?>><?php echo $label; ?></option>
                         
            <?php endforeach; ?>
                
        </select>

    <?php }
}

endif;