<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\form\TabbedSettingsPage' ) ) :

    /**
     * A Tabbed Settings page.
     *
     * @author Eric Green <eric@smartcat.ca>
     * @since 1.0.0
     * @package smartcat\admin
     */
    class TabbedSettingsPage extends SettingsPage {
        protected $tabs;

        public function __construct( array $config ) {
            parent::__construct( $config );

            foreach( $config['tabs'] as $tab => $title ) {
                $this->tabs[ $tab ] = $title;
            }
        }

        /**
         * @param SettingsSection $section
         * @param string $tab
         */
        public function add_section( SettingsSection $section, $tab = '' ) {
            $this->sections[ $section->get_slug() ] = array( 'tab' => $tab, 'section' => $section );
        }

        public function register_sections() {
            foreach( $this->sections as $slug => $section ) {
                $section['section']->register( $this->menu_slug . '_' . $section['tab'] );
            }
        }

        public function render() {
            $active_tab = key( $this->tabs );

            if( !empty( $_REQUEST['tab'] ) && array_key_exists( $_REQUEST['tab'], $this->tabs ) ) {
                $active_tab = $_REQUEST['tab'];
            }

            ?>


            <div class="wrap">

                <h2><?php echo $this->page_title; ?></h2>

                <?php  if( $this->type == 'menu' || $this->type == 'submenu' ) : ?>

                    <?php settings_errors( $this->menu_slug ); ?>

                <?php endif; ?>

                <h2 class="nav-tab-wrapper">

                    <?php foreach( $this->tabs as $tab => $title ) : ?>

                        <a href="<?php echo 'admin.php?page=' . $this->menu_slug . '&tab=' . $tab; ?>"
                           class="nav-tab <?php echo $active_tab == $tab ? 'nav-tab-active' : ''; ?>">

                            <?php echo $title; ?>

                        </a>

                    <?php endforeach; ?>

                </h2>

                <form method="post" action="options.php">

                    <?php settings_fields( $this->menu_slug . '_' .$active_tab ); ?>

                    <?php do_settings_sections( $this->menu_slug . '_' . $active_tab ); ?>

                    <?php submit_button(); ?>

                </form>

            </div>

        <?php }
    }

endif;