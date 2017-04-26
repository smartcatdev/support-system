<?php

namespace smartcat\admin;

if( !class_exists( '\smarcat\admin\TabbedMenuPage' ) ) :

class TabbedMenuPage extends AbstractMenuPage {

    protected $tabs = array();
    protected $active;

    public function __construct( array $config ) {
        parent::__construct( $config );

        if( isset( $config['tabs'] ) ) {
            $this->tabs = apply_filters( $this->menu_slug . '_tabs', $config['tabs'] );
        }

        $this->active = key( $this->tabs );

        if( !empty( $_REQUEST['tab'] ) && array_key_exists( $_REQUEST['tab'], $this->tabs ) ) {
            $this->active = $_REQUEST['tab'];
        }
    }

    public function render() { ?>

        <div class="wrap">

            <h2><?php _e( 'Reports', \SmartcatSupport\PLUGIN_ID ); ?></h2>

            <h2 class="nav-tab-wrapper">

                <?php foreach( apply_filters( $this->menu_slug . '_tabs', $this->tabs ) as $id => $tab ) : ?>

                    <a class="nav-tab <?php echo $this->active == $id ? 'nav-tab-active' : ''; ?>"
                       href="<?php echo 'admin.php?page=' . $this->menu_slug . '&tab=' . $id ?>"><?php echo $tab->title; ?></a>

                <?php endforeach; ?>

            </h2>

            <?php $this->tabs[ $this->active ]->render(); ?>

        </div>

    <?php }

}

endif;