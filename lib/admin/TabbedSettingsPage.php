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

    public function render() { ?>

        <div class="wrap">

            <h2><?php echo $this->page_title; ?></h2>

            <?php  if( $this->type == 'menu' || $this->type == 'submenu' ) : ?>

                <?php settings_errors(); ?>

            <?php endif; ?>

            <h2 class="nav-tab-wrapper">

                <?php foreach( $this->tabs as $tab => $title ) : ?>

                    <a href="<?php echo 'admin.php?page=' . $this->menu_slug . '&tab=' . $tab; ?>" class="nav-tab"><?php echo $title; ?></a>

                <?php endforeach; ?>

            </h2>

            <form method="post" action="options.php">

                <?php settings_fields( $this->menu_slug ); ?>

                    <?php if( !empty( $_REQUEST['tab'] ) ) : ?>

                        <?php do_settings_sections( $_REQUEST['tab'] ); ?>

                    <?php else : ?>

                        <?php do_settings_sections( key( $this->tabs ) ); ?>

                    <?php endif; ?>

                <?php  submit_button(); ?>

            </form>

        </div>

    <?php }
}

endif;