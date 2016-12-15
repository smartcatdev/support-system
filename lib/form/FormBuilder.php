<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\FormBuilder' ) ) :

class FormBuilder {
    protected $id;
    protected $fields = array();
    protected $method;
    protected $action;
    
    public function __construct( $id, $method = 'POST', $action = '?' ) {
        $this->id = $id;
        $this->method = $method;
        $this->action = $action;
    }
    
    public function add( $class, $id, $args = array() ) {
        $this->fields[ $id ] = new $class( $id, $args );
        
        return $this;
    }
    
    public function get_form() {
        return new Form( $this->id, $this->fields, $this->method, $this->action );
    }
    
    public function create_constraint( $class, $message = '', $args = [] ) {
        return new $class( $message, $args );
    }

    public function clear_config() {
        $this->fields = array();
    }
}

endif;