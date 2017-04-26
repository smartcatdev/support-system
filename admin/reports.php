<?php

namespace SmartcatSupport;

$active_tab = key( $this->tabs );

if( !empty( $_REQUEST['tab'] ) && array_key_exists( $_REQUEST['tab'], $this->tabs ) ) {
    $active_tab = $_REQUEST['tab'];
}

?>

<div class="wrap">

    <h2><?php _e( 'Reports', \SmartcatSupport\PLUGIN_ID ); ?></h2>

    <h2 class="nav-tab-wrapper">

        <?php foreach( $this->tabs as $tab => $title ) : ?>

            <a class="nav-tab <?php echo $active_tab == $tab ? 'nav-tab-active' : ''; ?>"
               href="<?php echo 'admin.php?page=ucare_support&tab=' . $tab ?>"><?php echo $title; ?></a>

        <?php endforeach; ?>

    </h2>

    <?php do_action( 'support_report_tab' . $active_tab ); ?>

</div>
