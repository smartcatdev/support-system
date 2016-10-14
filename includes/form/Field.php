<?php

namespace SmartcatSupport\form;

use SmartcatSupport\action\ActionListener;

abstract class Field extends ActionListener {
    protected $id;
    protected $default;
    protected $title;
    protected $desc;
 
    public function __construct( $id, $title, array $args = [] ) {
        $this->set_id( $id )
            ->set_title( $title );
        
        if( array_key_exists( 'desc', $args ) ) {
            $this->set_desc( $args['desc'] );
        }
        
        if( array_key_exists( 'default', $args ) ) {
            $this->set_default( $args['default'] );
        }
    }
    
    public function get_desc() {
        return $this->desc;
    }

    public function set_desc($desc) {
        $this->desc = $desc;
        return $this;
    }
    
    public function get_id() {
        return $this->id;
    }

    public function get_default() {
        return $this->default;
    }

    public function get_title() {
        return $this->title;
    }

    public function set_id( $id ) {
        $this->id = $id;
        return $this;
    }

    public function set_default( $default ) {
        $this->default = $default;
        return $this;
    }

    public function set_title( $title ) {
        $this->title = $title;
        return $this;
    }
    
    public function validate( $value ) {
        return $value;
    }
    
    abstract public function render();
}