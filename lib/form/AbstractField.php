<?php

namespace smartcat\form;

if( !class_exists('\smartcat\form\AbstractField') ) :

abstract class AbstractField {
    public $id;
    public $value;
    public $label;
    public $desc;
    public $error_message;
    protected $constraints = array();
    protected $sanitize_callback;
 
    public function __construct( array $args ) {
        $this->id = $args['id'];

        if( !empty( $args['error_msg'] ) ) {
            $this->error_message = $args['error_msg'];
        }
        
        if( !empty( $args['label'] ) ) {
            $this->label = $args['label'];
        }

        if( !empty( $args['constraints'] ) ) {
            $this->constraints = $args['constraints'];
        }
        
        if( !empty( $args['desc'] ) ) {
            $this->desc = $args['desc'];
        }
        
        if( !empty( $args['value'] ) ) {
            $this->value = $args['value'];
        }

        if( !empty( $args['sanitize_callback'] ) ) {
            $this->sanitize_callback = $args['sanitize_callback'];
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

    public abstract function render();
}

endif;