<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\SettingsPage' ) ) :

class SettingsPage {
    protected $type;
    protected $page_title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $parent_menu;
    protected $icon;
    protected $position;
    protected $sections = [];

    public function __construct( array $config ) {
        $this->type = $config['type'];
        $this->page_title = $config['page_title'];
        $this->menu_title = $config['menu_title'];
        $this->menu_slug = $config['menu_slug'];

        $this->parent_menu = isset( $config['parent_menu'] ) ? $config['parent_menu'] : '';
        $this->capability = isset( $config['capability'] ) ? $config['capability'] : 'manage_options';
        $this->icon = isset( $config['icon'] ) ? $config['icon'] : 'dashicons-admin-generic';
        $this->position = isset( $config['position'] ) ? $config['position'] : 100;
    }

    public function register() {
        add_action( 'admin_menu', array( $this, 'register_page' ) );
        add_action( 'admin_init', array( $this, 'register_sections' ) );
    }

    public function unregister() {
        remove_action( 'admin_menu', array( $this, 'register_page' ) );
        remove_action( 'admin_init', array( $this, 'register_sections' ) );
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

    public function add_section( SettingsSection $section ) {
        $this->sections[ $section->get_slug() ] = $section;
    }

    public function remove_section( $id ) {
        $result = $this->get_section( $id );

        if( $result !== false ) {
            unset( $this->sections[ $id ] );
        }

        return $result;
    }

    public function set_sections( array $sections ) {
        $this->sections = $sections;
    }

    public function get_section( $id ) {
        $section = false;

        if( isset( $this->sections[ $id ] ) ) {
            $section = &$this->sections[ $id ];
        }

        return $section;
    }

    public function register_sections() {
        foreach( $this->sections as $section ) {
            $section->register( $this->menu_slug );
        }
    }

    public function render() { ?>

        <div class="wrap">

            <h2><?php echo $this->page_title; ?></h2>

            <?php  if( $this->type == 'menu' || $this->type == 'submenu' ) : ?>

               <?php settings_errors(); ?>

            <?php endif; ?>

            <form method="post" action="options.php">

                <?php
                    settings_fields( $this->menu_slug );
                    do_settings_sections( $this->menu_slug );
                    submit_button();
                ?>

            </form>

        </div>

    <?php }
}

endif;