<?php

namespace smartcat\admin;

use smartcat\core\HookSubscriber;

if( !class_exists( '\smartcat\admin\SettingsPage' ) ) :


    /**
     * A Standard single admin settings page.
     *
     * @package smartcat\admin
     * @author Eric Green <eric@smartcat.ca>
     * @since 1.0.0
     */
    class SettingsPage implements HookSubscriber {
        protected $type;
        protected $page_title;
        protected $menu_title;
        protected $capability;
        protected $menu_slug;
        protected $parent_menu = '';
        protected $icon;
        protected $position;
        protected $sections = [];

        /**
         * SettingsPage constructor.
         *
         * @param array $config
         *  $args = [
         *      'page_title'    => (string) Title to display on the page. Required.
         *      'menu_title'    => (string) Title to display in admin menu. Required.
         *      'menu_slug'     => (string) Slug name of settings page. Required.
         *      'type'          => (string) The type of settings page. Default: options.
         *      'capability'    => (string) Minimum capability required. Default: manage_options.
         *      'icon'          => (string) Icon to display in admin menu. Default: dashicons-admin-generic.
         *      'parent_menu'   => (string) Where the page should appear if it is a child of another.
         *      'position'      => (int) Position page should appear in menu. Default: 100.
         *    ]
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         */
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

        /**
         * Add the page to the admin menu.
         *
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         */
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

        /**
         * Adds a section to the page.
         *
         * @param SettingsSection $section
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         */
        public function add_section( SettingsSection $section ) {
            $this->sections[ $section->get_slug() ] = $section;
        }

        /**
         * Removes the section from the page and returns it.
         *
         * @param string $id The slug id of the section to remove.
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         * @return SettingsSection
         */
        public function remove_section( $id ) {
            $result = $this->get_section( $id );

            if( $result !== false ) {
                unset( $this->sections[ $id ] );
            }

            return $result;
        }

        /**
         * Gets a reference to a section of the settings page.
         *
         * @param string $id The slug id of the settings page.
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         * @return SettingsSection
         */
        public function get_section( $id ) {
            $section = false;

            if( isset( $this->sections[ $id ] ) ) {
                $section = &$this->sections[ $id ];
            }

            return $section;
        }

        /**
         * Register each section with Settings API.
         *
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         */
        public function register_sections() {
            foreach( $this->sections as $section ) {
                $section->register( $this->menu_slug );
            }
        }

        public function subscribed_hooks() {
            return array(
                'admin_menu' => array( 'register_page' ),
                'admin_init' => array( 'register_sections' )
            );
        }

        /**
         * Output the settings page.
         *
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         */
        public function render() { ?>

            <div class="wrap">

                <h2><?php echo $this->page_title; ?></h2>

                <?php if( $this->type == 'menu' || $this->type == 'submenu' ) : ?>

                   <?php settings_errors( $this->menu_slug ); ?>

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