<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\SettingsField' ) ) :

abstract class SettingsField {
    protected $id;
    protected $option;
    protected $label;
    protected $desc = '';
    protected $value = '';
    protected $props = array();
    protected $class = array();
    protected $args = array();
    protected $validators = array();

    public function __construct( array $args ) {
        foreach( $args as $arg => $value ) {
            if( property_exists( __CLASS__, $arg ) ) {
                $this->{ $arg } = $value;
            }
        }

        $this->args['label_for'] = $this->id;
    }

    public function get_id() {
        return $this->id;
    }

    public function register( $menu_slug, $section_slug ) {
        add_settings_field( $this->id, $this->label, array( $this, 'render' ), $menu_slug, $section_slug, $this->args );
        register_setting( $menu_slug, $this->option, array( $this, 'validate' ) );
    }

    public function validate( $value ) {
        foreach( $this->validators as $validator ) {
            $value = $validator->filter( $value );
        }

        return $value;
    }

    protected function classes() {
        if( !empty( $this->class ) ) {
            echo ' class="' . implode( ' ', $this->class ) . '" ';
        }
    }

    protected function props() {
        if( !empty( $this->props ) ) {
            foreach( $this->props as $prop => $values ) {
                echo $prop . '="' . implode( ' ', $values ) . '"';
            }
        }
    }

    public abstract function render( array $args );
}

endif;