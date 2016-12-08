<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\form\TabbedSettingsPage' ) ) :

class TabbedSettingsPage extends SettingsPage {

    protected $tabs;

    public function __construct( array $config ) {
        parent::__construct( $config );

        $this->tabs = $config['tabs'];
    }


    public function add_section( SettingsSection $section, $tab = '' ) {
        $this->sections[ $section->get_slug() ] = array( 'tab' => $tab, 'section' => $section );
    }

    public function register_sections() {
        foreach( $this->sections as $slug => $section ) {
            $section['section']->register( $section['tab'] );
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

                <?php settings_errors(); ?>

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

                <?php settings_fields( $active_tab ); ?>

                <?php do_settings_sections( $active_tab ); ?>

                <?php submit_button(); ?>

            </form>

        </div>

    <?php }
}

endif;