<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $start;
    private $end;

    public function __construct( $title ) {
        parent::__construct( $title );

        $this->start = new \DateTime('7 days ago');
        $this->end = new \DateTime('today');
    }

    private function format( $date ) {
        return $date->format( 'd-m-Y' );
    }

    public function render() { ?>

        <div class="stats-overview stat-section">

            <div class="stats-header">

                <form class="form-inline" method="post">

                    <div class="control-group">

                        <select class="date-range-select form-control">
                            <option value="last_week"><?php _e( 'Last 7 Days', \SmartcatSupport\PLUGIN_ID ); ?></option>
                            <option value="this_month"><?php _e( 'This Month', \SmartcatSupport\PLUGIN_ID ); ?></option>
                            <option value="last_month"><?php _e( 'Last Month', \SmartcatSupport\PLUGIN_ID ); ?></option>
                            <option value="this_year"><?php _e( 'This Year', \SmartcatSupport\PLUGIN_ID ); ?></option>
                            <option value="custom"><?php _e( 'Custom Range', \SmartcatSupport\PLUGIN_ID ); ?></option>
                        </select>

                    </div>

                    <div class="date-range control-group hidden">
                        <input name="start_date" class="date start_date" type="text" value="<?php echo $this->format( $this->start ); ?>" />
                        <span><?php _e( 'to', \SmartcatSupport\PLUGIN_ID ); ?></span>
                        <input name="end_date" class="date end_date" type="text" value="<?php echo $this->format( $this->end ); ?>"/>
                    </div>

                    <div class="control-group">
                        <button type="submit" class="form-control button button-secondary"><?php _e( 'Go', \SmartcatSupport\PLUGIN_ID ); ?></button>
                    </div>

                </form>

            </div>

            <div class="stats-chart-wrapper">
                <div id="stats-chart" class="ct-chart ct-golden-section"></div>
            </div>

        </div>

    <?php }
}