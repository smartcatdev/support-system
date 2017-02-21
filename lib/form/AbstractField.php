<?php

namespace smartcat\form;

if( !class_exists('\smartcat\form\AbstractField') ) :

abstract class AbstractField {
    public $id;
    public $name;
    public $value = '';
    public $label;
    public $desc;
    public $class = array();
    public $props = array();
    public $error_msg;
    protected $constraints = array();
    protected $sanitize_callback;
 
    public function __construct( array $args ) {
        foreach( $args as $arg => $value ) {
            if( property_exists( __CLASS__, $arg ) ) {
                $this->{ $arg } = $value;
            }
        }
    }

    public function sanitize( $value ) {
        $sanitized_value = $value ;

        if( isset( $this->sanitize_callback ) ) {
            $sanitized_value = call_user_func_array( $this->sanitize_callback, [ $value ] );
        }

        return $sanitized_value;
    }
    
    public function validate( $value ) {
        $valid = true;
 
        foreach( $this->constraints as $constraint ) {
            if( !$constraint->is_valid( $value ) ) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    public function render() {}

    protected function classes() {
        if( !empty( $this->class ) ) {
            echo ' class="' . implode( $this->class, ' ' ) . '" ';
        }
    }

    protected function props() {
        if( !empty( $this->props ) ) {
            foreach( $this->props as $prop => $values ) {
                echo $prop . '="' . implode( $values, ' ' ) . '"';
            }
        }
    }
}

endif;