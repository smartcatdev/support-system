<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    public function render() { ?>

        <div>

            <?php echo \SmartcatSupport\statprocs\get_ticket_count(); ?>
            <?php echo \SmartcatSupport\statprocs\get_unclosed_tickets(); ?>

        </div>

    <?php }
}