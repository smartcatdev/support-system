<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\SettingsSection') ) :
    /**
     * Class SettingsSection
     * @deprecated
     * @package smartcat\admin
     */
class SettingsSection {
    protected $slug;
    protected $title;
    protected $fields;

    public function __construct( $slug, $title, $fields = array() ) {
        $this->slug = $slug;
        $this->title = $title;
        $this->fields = $fields;
    }

    public function get_slug() {
        return $this->slug;
    }

    public function register( $parent_slug ) {
        add_settings_section( $this->slug, $this->title, array( $this, 'section' ), $parent_slug );

        foreach( $this->fields as $field ) {
            $field->register( $parent_slug, $this->slug );
        }
    }

    public function section( $args ) {
        // Subclasses can override this to customize section appearance
    }

    public function add_field( SettingsField $field ) {
        $this->fields[ $field->get_id() ] = $field;

        return $this;
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