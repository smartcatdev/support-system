<?php

namespace smartcat\admin;

if( !class_exists( '\smarcat\admin\TabbedMenuPage' ) ) :

class TabbedMenuPage extends MenuPage {

    public $tabs = array();

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

        $active = array_keys( $this->tabs )[0];

        if( !empty( $_REQUEST['tab'] ) && array_key_exists( $_REQUEST['tab'], $this->tabs ) ) {
            $active = $_REQUEST['tab'];
        }

        return $active;
    }

    public function render() { ?>

        <div id="<?php echo $this->menu_slug . '_menu_page'; ?>" class="wrap">

            <?php $this->do_header(); ?>

            <h2 class="nav-tab-wrapper">

                <?php foreach ( apply_filters( $this->menu_slug . '_tabs', $this->tabs ) as $id => $tab ) : ?>

                    <?php $url = apply_filters( 'tab_url_' . $id, 'admin.php?page=' . $this->menu_slug . '&tab=' . $id ); ?>

                    <a class="nav-tab <?php echo $this->active_tab() == $id ? 'nav-tab-active' : ''; ?>"
                       href="<?php echo esc_url( $url ); ?>"><?php echo $tab->title; ?></a>

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