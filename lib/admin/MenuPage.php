<?php

namespace smartcat\admin;

if( ! class_exists( 'smartcat\core\MenuPage' ) ) :

class MenuPage {

    public $type;
    public $page_title;
    public $menu_title;
    public $capability;
    public $menu_slug;
    public $parent_menu = '';
    public $icon;
    public $position;
    public $render;
    public $onload;

    public function __construct( array $config ) {
        $this->menu_title = $config['menu_title'];
        $this->menu_slug = $config['menu_slug'];

        $this->render = isset( $config['render'] ) ? $config['render'] : true;
        $this->page_title = isset( $config['page_title'] ) ? $config['page_title'] : '';
        $this->type = isset( $config['type'] ) ? $config['type'] : 'options';
        $this->onload = isset( $config['onload'] ) ? $config['onload'] : '';

        if( $this->type == 'submenu' ) {
            $this->parent_menu = $config['parent_menu'];
        }

        $this->capability = isset( $config['capability'] ) ? $config['capability'] : 'manage_options';
        $this->icon = isset( $config['icon'] ) ? $config['icon'] : 'dashicons-admin-generic';
        $this->position = isset( $config['position'] ) ? $config['position'] : 100;

        $this->init();
    }

    public function init() {
        add_action( 'admin_menu', array( $this, 'register_page' ) );
    }

    public function register_page() {
        $config = array();

        if( $this->type == 'submenu' && $this->parent_menu != '' ) {
            $config[] = $this->parent_menu;
        }

        $config[] = $this->page_title;
        $config[] = $this->menu_title;
        $config[] = $this->capability;
        $config[] = $this->menu_slug;

        if( is_callable( $this->render ) ) {
            $config[] = $this->render;
        } else if( is_file( $this->render ) ) {
            $config[] = function () { include_once $this->render; };
        } else {
            $config[] = $this->render ? array( $this, 'render' ) : '';
        }

        $config[] = $this->icon;
        $config[] = $this->position;

        $hook = call_user_func_array( "add_{$this->type}_page", $config );

        if( $hook ) {
            add_action( 'load-' . $hook, is_callable( $this->onload ) ? $this->onload : array( $this, 'on_load' ) );
        }
    }

    public function on_load() { }

    public function render() {}

    protected function do_header() {
        do_action( $this->menu_slug . '_admin_page_header' );

        if( !empty( $this->page_title ) ) {
            printf( '<h2>%1$s</h2>', $this->page_title );
        }
    }

}

endif;
