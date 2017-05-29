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
    class SettingsPage extends MenuPage {

        protected $sections = array();

        public function __construct( array $config )  {
            parent::__construct( $config );

            $this->init();
        }

        public function init() {
            add_action( 'admin_menu', array( $this, 'register_page' ) );
            add_action( 'admin_init', array( $this, 'register_sections' ) );
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

        /**
         * Output the settings page.
         *
         * @author Eric Green <eric@smartcat.ca>
         * @since 1.0.0
         */
        public function render() { ?>

            <div id="<?php echo $this->menu_slug . '_menu_page'; ?>" class="wrap">

                <?php $this->do_header(); ?>

                <?php if( $this->type == 'menu' || $this->type == 'submenu' ) : ?>

                   <?php settings_errors( $this->menu_slug ); ?>

                <?php endif; ?>

                <form method="post" action="options.php">

                    <?php settings_fields( $this->menu_slug ); ?>
                    <?php do_settings_sections( $this->menu_slug ); ?>
                    <?php submit_button(); ?>

                </form>

                <?php do_action( $this->menu_slug . '_menu_page' ); ?>

            </div>

        <?php }
    }

endif;