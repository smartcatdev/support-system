<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\SettingsPage' ) ) :


    /**
     * A Standard single admin settings page.
     *
     * @package smartcat\admin
     * @author Eric Green <eric@smartcat.ca>
     * @since 1.0.0
     */
    class SettingsPage extends AbstractMenuPage {

        protected $sections = [];

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