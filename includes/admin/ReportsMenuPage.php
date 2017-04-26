<?php

namespace SmartcatSupport\admin;


use smartcat\admin\AbstractMenuPage;

class ReportsMenuPage extends AbstractMenuPage {

    private $tabs = array();
    private $active;

    public function __construct() {
        parent::__construct( array(
            'type'          => 'submenu',
            'parent_menu'   => 'ucare_support',
            'menu_slug'     => 'ucare_support',
            'page_title'    => __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            'menu_title'    => __( 'Reports', \SmartcatSupport\PLUGIN_ID ),
            'capability'    => 'manage_support'

        ) );

        $this->tabs['overview'] = new ReportsOverviewTab( __( 'Overview', \SmartcatSupport\PLUGIN_ID ) );

        $this->active = key( $this->tabs );

        if( !empty( $_REQUEST['tab'] ) && array_key_exists( $_REQUEST['tab'], $this->tabs ) ) {
            $this->active = $_REQUEST['tab'];
        }
    }

    public function subscribed_hooks() {
        return array(
            'support_menu_register' => array( 'register_page', 1 ),
        );
    }

    public function render() { ?>

        <div class="wrap">

            <h2><?php _e( 'Reports', \SmartcatSupport\PLUGIN_ID ); ?></h2>

            <h2 class="nav-tab-wrapper">

                <?php foreach( apply_filters( 'support_reports_tabs', $this->tabs ) as $id => $tab ) : ?>

                    <a class="nav-tab <?php echo $this->active == $id ? 'nav-tab-active' : ''; ?>"
                       href="<?php echo 'admin.php?page=ucare_support&tab=' . $id ?>"><?php echo $tab->title; ?></a>

                <?php endforeach; ?>

            </h2>

            <?php $this->tabs[ $this->active ]->render(); ?>

        </div>

    <?php }
}
