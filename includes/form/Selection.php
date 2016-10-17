<?php

namespace SmartcatSupport\form;

/**
 * Abstract base class for select type fields
 *
 * @author ericg
 */
abstract class Selection extends Field {
    protected $options;

    public function __construct( $id, $title, array $args ) {
        parent::__construct( $id, $title, $args );
        
        $this->options = $args['options'];
    }
    
    public function validate( $value ) {
        if( !array_key_exists( $value, $this->options ) ) {
            $value = $this->default;
        }
        
        return $value;
    }
}
