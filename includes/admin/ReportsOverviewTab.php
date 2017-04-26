<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    public function render() { ?>

        <div class="stats-overview stat-section">
            <div class="stats-header">
                <div class="form-inline">
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
                        <input class="date" type="text" />
                        <span><?php _e( 'to', \SmartcatSupport\PLUGIN_ID ); ?></span>
                        <input class="date" type="text" />
                    </div>
                    <div class="control-group">
                        <button type="submit" class="form-control button button-secondary"><?php _e( 'Go', \SmartcatSupport\PLUGIN_ID ); ?></button>
                    </div>
                </div>
            </div>
        </div>

    <?php }
}