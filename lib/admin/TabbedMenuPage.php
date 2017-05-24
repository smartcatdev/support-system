<?php

namespace smartcat\admin;

if( !class_exists( '\smarcat\admin\TabbedMenuPage' ) ) :

class TabbedMenuPage extends MenuPage {

    protected $tabs = array();

    public function __construct( array $config ) {
        parent::__construct( $config );

        if( isset( $config['tabs'] ) ) {
            foreach ( $config['tabs'] as $tab ) {
                $this->add_tab( $tab );
            }
        }
    }

    public function add_tab( MenuPageTab $tab ) {
        if( !isset( $this->tabs[ $tab->slug ] )  ) {
            $this->tabs[ $tab->slug ] = $tab;
            $tab->page = $this->menu_slug;
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

        <div id="<?php echo $this->menu_slug . '_menu_page'; ?>" class="wrap">

            <?php $this->do_header(); ?>

            <h2 class="nav-tab-wrapper">

                <?php foreach( $this->tabs as $id => $tab ) : ?>

                    <a class="nav-tab <?php echo $this->active_tab() == $id ? 'nav-tab-active' : ''; ?>"
                       href="<?php echo 'admin.php?page=' . $this->menu_slug . '&tab=' . $id ?>"><?php echo $tab->title; ?></a>

                <?php endforeach; ?>

            </h2>

            <div class="tabs-content">

                <?php $this->tabs[ $this->active_tab() ]->render(); ?>

            </div>

            <?php do_action( $this->menu_slug . '_menu_page' ); ?>

        </div>

    <?php }

}

endif;