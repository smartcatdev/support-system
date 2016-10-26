<?php

namespace SmartcatSupport\form;

use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Description of SelectBox
 *
 * @author ericg
 */
class SelectBox extends Field {
    protected $options;

    public function __construct( $id, array $args ) {
        parent::__construct( $id, $args );
        
        $this->options = $args['options'];
    }
    
    public function render() { ?>

        <select id="<?php esc_attr_e( $this->id ); ?>"
            name="<?php esc_attr_e( $this->id ); ?>">

            <?php foreach( $this->options as $value => $label ) : ?>
                
                <option value="<?php esc_attr_e( $value ); ?>"
                    <?php selected( $value, $this->value, true ); ?>>
                
                    <?php esc_html_e( __( $label, TEXT_DOMAIN ) ); ?>
                        
                </option>
                         
            <?php endforeach; ?>
                
        </select>

    <?php }
}
