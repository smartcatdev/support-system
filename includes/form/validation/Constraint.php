<?php

namespace SmartcatSupport\form\validation;

/**
 * Description of Constraint
 *
 * @author Eric Green <eric@smartcat.ca>
 */
abstract class Constraint {
    protected $message;

    public function __construct( $message = '', $args = null ) {
        $this->set_message( $message );
    }
    
    public function get_message() {
        return $this->message;
    }

    public function set_message( $message ) {
        $this->message = $message;
    }

    abstract public function is_valid( $value );
}
