<?php

namespace smartcat\admin;


use smartcat\core\HookSubscriber;

if( ! class_exists( 'smartcat\core\AbstractMenuPage' ) ) :

abstract class AbstractMenuPage implements HookSubscriber {

    protected $type;
    protected $page_title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $parent_menu = '';
    protected $icon;
    protected $position;

    public function __construct( array $config ) {
        $this->menu_title = $config['menu_title'];
        $this->menu_slug = $config['menu_slug'];

        $this->page_title = isset( $config['page_title'] ) ? $config['page_title'] : '';
        $this->type = isset( $config['type'] ) ? $config['type'] : 'options';

        if( $this->type == 'submenu' ) {
            $this->parent_menu = $config['parent_menu'];
        }

        $this->capability = isset( $config['capability'] ) ? $config['capability'] : 'manage_options';
        $this->icon = isset( $config['icon'] ) ? $config['icon'] : 'dashicons-admin-generic';
        $this->position = isset( $config['position'] ) ? $config['position'] : 100;
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
        $config[] = array( $this, 'render' );
        $config[] = $this->icon;
        $config[] = $this->position;

        call_user_func_array( "add_{$this->type}_page", $config );
    }

    abstract public function render();

    protected function do_header() {
        do_action( $this->menu_slug . '_admin_page_header' );

        if( !empty( $this->page_title ) ) {
            printf( '<h2>%1$s</h2>', $this->page_title );
        }
    }

    public function subscribed_hooks() {
        return array(
            'admin_menu' => array( 'register_page' ),
        );
    }

}

endif;