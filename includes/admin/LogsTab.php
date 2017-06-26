<?php


namespace ucare\admin;


use smartcat\admin\MenuPageTab;

class LogsTab extends MenuPageTab {

    public function __construct() {
        parent::__construct( array(
            'slug'  => 'logs',
            'title' => __( 'Logs', 'ucare' )
        ) );

        if( isset( $_GET['clear'] ) ) {
            $this->clear_logs();

            wp_redirect( admin_url( '?page=' . $_GET['page'] . '&tab=' . $_GET['tab'] ) );
        }
    }

    public  function render() { ?>

        <form method="get">

            <div class="reports-wrapper">

                <input type="hidden" name="page" value="<?php echo $this->page; ?>" />
                <input type="hidden" name="tab" value="<?php echo $this->slug; ?>" />

                <?php

                    $table = new LogsTable();

                    $table->prepare_items();
                    $table->display();

                ?>

            </div>

        </form>

    <?php }

    private function clear_logs() {
        global $wpdb;

        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}ucare_logs" );
    }
}
