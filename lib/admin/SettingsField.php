<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\SettingsField' ) ) :

abstract class SettingsField {
    protected $id;
    protected $label;
    protected $desc = '';
    protected $value = '';
    protected $args = array();
    protected $validators = array();

    public function __construct( array $args ) {
        $this->id = $args['id'];
        $this->label = $args['label'];

        if( !empty( $args['value'] ) ) {
            $this->value = $args['value'];
        }

        if( !empty( $args['validators' ] ) ) {
            $this->validators = $args['validators'];
        }

        if( !empty( $args['desc' ] ) ) {
            $this->desc = $args['desc'];
        }

        $this->args['label_for'] = $this->id;
    }

    public function get_id() {
        return $this->id;
    }

    public function register( $menu_slug, $section_slug ) {
        add_settings_field( $this->id, $this->label, array( $this, 'render' ), $menu_slug, $section_slug, $this->args );
        register_setting( $menu_slug, $this->id, array( $this, 'validate' ) );
    }

    public function validate( $value ) {
        foreach( $this->validators as $validator ) {
            $value = $validator->filter( $value );
        }

        return $value;
    }

    public abstract function render( array $args );
}

endif;