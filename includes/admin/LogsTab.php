<?php


namespace ucare\admin;


use smartcat\admin\MenuPageTab;

class LogsTab extends MenuPageTab {

    public function __construct() {
        parent::__construct( array(
            'slug'  => 'logs',
            'title' => __( 'Logs', \ucare\PLUGIN_ID )
        ) );
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
}
