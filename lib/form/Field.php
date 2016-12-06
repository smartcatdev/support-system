<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\Field' ) ) :

abstract class Field {
    protected $id;
    protected $value;
    protected $label;
    protected $desc;
    protected $class;
    protected $error_message;
    protected $constraints = array();
    protected $data_attrs = array();
    protected $sanitize_callback;
 
    public function __construct( $id, array $args = array() ) {
        $this->set_id( $id );

        if( isset( $args['error_msg'] ) ) {
            $this->set_error_message( $args['error_msg'] );
        }
        
        if( isset( $args['label'] ) ) {
            $this->set_label( $args['label'] );
        }

        if( isset( $args['constraints'] ) ) {
            $this->constraints = $args['constraints'];
        }
        
        if( array_key_exists( 'desc', $args ) ) {
            $this->set_desc( $args['desc'] );
        }
        
        if( array_key_exists( 'value', $args ) ) {
            $this->set_value( $args['value'] );
        }

        if( isset( $args['sanitize_callback'] ) ) {
            $this->sanitize_callback = $args['sanitize_callback'];
        }

        if( isset( $args['class'] ) ) {
            $this->class = $args['class'];
        }

        if( isset( $args['data_attrs'] ) && is_array( $args['data_attrs'] ) ) {
            $this->data_attrs = $args['data_attrs'];
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
    
    // <editor-fold defaultstate="collapsed" desc="Getters / Setters">
    public function get_desc() {
        return $this->desc;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_value() {
        return $this->value;
    }

    public function get_label() {
        return $this->label;
    }

    public function set_id( $id ) {
        $this->id = $id;
        return $this;
    }

    public function get_error_message() {
        return $this->error_message;
    }

    public function set_error_message( $error_message ) {
        $this->error_message = $error_message;
        return $this;
    }

    public function set_desc( $desc ) {
        $this->desc = $desc;
        return $this;
    }

    public function set_value( $value ) {
        $this->value = $value;
        return $this;
    }

    public function set_label( $label ) {
        $this->label = $label;
        return $this;
    }
// </editor-fold>
}

endif;