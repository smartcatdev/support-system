<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\Form' ) ) :

class Form {
    public $id;
    public $method;
    public $action;
    public $valid = false;
    public $fields = array();
    public $data = array();
    public $errors = array();

    public function __construct( $id, $method = '', $action = '' ) {
        $this->id = $id;
        $this->method = strtoupper( $method );
        $this->action = $action;
    }

    public function add_field( AbstractField $field ) {
        $this->fields[ $field->name ] = $field;

        return $this;
    }

    public function is_valid() {
        $valid = true;

        if ( $this->is_submitted() ) {
            foreach ( $this->fields as $name => $field ) {
                if ( isset( $_REQUEST[ $name ] ) && $field->validate( $_REQUEST[ $name ] ) ) {
                    $this->data[ $name ] = $field->sanitize( $_REQUEST[ $name ] );
                } else {
                    $this->errors[ $name ] = $field->error_msg;
                    $valid = false;
                }
            }
        } else {
            $valid = false;
        }

        $this->valid = $valid;

        return $valid;
    }

    public function is_submitted() {
        return isset( $_REQUEST[ $this->id ] );
    }
}

endif;