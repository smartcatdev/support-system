<?php

namespace Smartcat\admin;

abstract class SettingsField {
    protected $slug;
    protected $title;
    protected $args = [];

    public function __construct( $slug, $title ) {
        $this->slug = $slug;
        $this->title = $title;
    }

    public function get_slug() {
        return $this->slug;
    }
    public function register( $menu_slug, $section_slug ) {
        add_settings_field( $this->slug, $this->title, [ &$this, 'render' ], $menu_slug, $section_slug, $this->args );
        register_setting( $menu_slug, $this->slug, [ &$this, 'validate' ] );
    }

    public function validate( $value ) {
        return $value;
    }

    public abstract function render( array $args );
}