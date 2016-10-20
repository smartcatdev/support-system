<?php

namespace SmartcatSupport\form;

abstract class Field {
    protected $id;
    protected $value;
    protected $label;
    protected $desc;
    protected $constraints = [];
 
    public function __construct( $id, array $args = [] ) {
        $this->set_id( $id );
        
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
    }
    
    public function validate( $value ) {
        $valid = true;
 
        foreach( $this->constraints as $constraint ) {
            if( !$constraint->is_valid( $value ) ) {
                $valid = $constraint->get_message();
                break;
            }
        }

        return $valid;
    }
    
    public function sanitize( $value ) {
        return $value;
    }
    
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

    public function set_id($id) {
        $this->id = $id;
        return $this;
    }


    public function set_desc($desc) {
        $this->desc = $desc;
        return $this;
    }

    public function set_value($value) {
        $this->value = $value;
        return $this;
    }

    public function set_label($label) {
        $this->label = $label;
        return $this;
    }

// </editor-fold>
}