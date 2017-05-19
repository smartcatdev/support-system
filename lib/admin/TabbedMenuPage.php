<?php

namespace smartcat\admin;

if( !class_exists( '\smarcat\admin\TabbedMenuPage' ) ) :

class TabbedMenuPage extends AbstractMenuPage {

    protected $tabs = array();

    public function __construct( array $config ) {
        parent::__construct( $config );

        if( isset( $config['tabs'] ) ) {
            $this->tabs = $config['tabs'];
        }

        foreach( $this->tabs as $slug => $tab ) {
            $tab->slug = $slug;
            $tab->page = $this->menu_slug;
        }
    }

    public function add_tab( $tab ) {
        if( !isset( $this->tabs[ $tab->slug ] )  ) {
            $this->tabs[ $tab->slug ] = $tab;
        }
    }

    private function active_tab() {
        $active = key( $this->tabs );

        if( !empty( $_REQUEST['tab'] ) && array_key_exists( $_REQUEST['tab'], $this->tabs ) ) {
            $active = $_REQUEST['tab'];
        }

        return $active;
    }

    public function render() { ?>

        <div class="wrap">

            <h2><?php _e( 'Reports', \ucare\PLUGIN_ID ); ?></h2>

            <h2 class="nav-tab-wrapper">

                <?php foreach( $this->tabs as $id => $tab ) : ?>

                    <a class="nav-tab <?php echo $this->active_tab() == $id ? 'nav-tab-active' : ''; ?>"
                       href="<?php echo 'admin.php?page=' . $this->menu_slug . '&tab=' . $id ?>"><?php echo $tab->title; ?></a>

                <?php endforeach; ?>

            </h2>

            <?php $this->tabs[ $this->active_tab() ]->render(); ?>

        </div>

    <?php }

}

endif;