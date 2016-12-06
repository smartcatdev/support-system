<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\SettingsSection') ) :

class SettingsSection {
    protected $slug;
    protected $title;
    protected $fields = [];

    public function __construct( $slug, $title ) {
        $this->slug = $slug;
        $this->title = $title;
    }

    public function get_slug() {
        return $this->slug;
    }

    public function register( $menu_slug ) {
        add_settings_section( $this->slug, $this->title, array( $this, 'section' ), $menu_slug );

        foreach( $this->fields as $field ) {
            $field->register( $menu_slug, $this->slug );
        }
    }

    public function section( $args ) {
        // Subclasses can overrride this to customize section appearance
    }

    public function add_field( SettingsField $field ) {
        $this->fields[ $field->get_slug() ] = $field;
    }

    public function remove_field( $id ) {
        $field = $this->get_field( $id );
        if( $field !== false ) {
            unset( $this->fields[ $field->get_id() ] );
        }
        return $field;
    }

    public function get_field( $id ) {
        $field = false;
        if( isset( $this->fields[ $id ] ) ) {
            $field = &$this->fields[ $id ];
        }
        return $field;
    }

    public function set_fields( array $fields ) {
        $this->fields = $fields;
    }
}

endif;