<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\ChoiceConstraint' ) ) :
    /**
     * @deprecated
     */
class ChoiceConstraint implements Constraint {
    protected $options = array();
    
    public function __construct( array $options ) {
        $this->options = $options;
    }
    
    public function is_valid( $value ) {
        return in_array( $value, $this->options );
    }
}

endif;