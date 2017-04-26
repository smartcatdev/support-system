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

        $this->page_title = $config['page_title'];
        $this->menu_title = $config['menu_title'];
        $this->menu_slug = $config['menu_slug'];

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

    public function subscribed_hooks() {
        return array(
            'admin_menu' => array( 'register_page' ),
        );
    }

}

endif;