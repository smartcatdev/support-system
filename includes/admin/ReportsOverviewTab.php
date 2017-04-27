<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $start;
    private $end;

    private $date_range_options;

    private $opened_tickets = array();
    private $closed_tickets = array();

    public function __construct() {

        parent::__construct( __( 'Overview', \SmartcatSupport\PLUGIN_ID ) );

        $this->date_range_options = array(
            'last_week'     => __( 'Last 7 Days', \SmartcatSupport\PLUGIN_ID ),
            'this_month'    => __( 'This Month', \SmartcatSupport\PLUGIN_ID ),
            'last_month'    => __( 'Last Month', \SmartcatSupport\PLUGIN_ID ),
            'this_year'     => __( 'This Year', \SmartcatSupport\PLUGIN_ID ),
            'custom'        => __( 'Custom Range', \SmartcatSupport\PLUGIN_ID ),
        );

        $start_init = '7 days ago';
        $end_init = 'now';

        if( isset( $_POST['start_date'] ) && isset( $_POST['end_date'] ) ) {
            $start = strtotime( $_POST['start_date'] );
            $end = strtotime( $_POST['end_date'] );

            if( $start <= $end ) {
                $min = strtotime('-2 years');
                $max = strtotime('now');

                $start_init = $start >= $min && $start <= $max ? date('y-m-d', $start) : '7 days ago';
                $end_init = $end >= $min && $end <= $max ? date('y-m-d', $end) : 'now';
            }
        }

        $this->start = new \DateTimeImmutable( $start_init );
        $this->end = new \DateTimeImmutable( $end_init );

        $ticket_stats = \SmartcatSupport\statprocs\tickets_overview_by_range( $this->start, $this->end );

        foreach( $ticket_stats as $stat ) {
            $this->opened_tickets[ $stat['date_formatted'] ] = $stat['opened'];
            $this->closed_tickets[ $stat['date_formatted'] ] = $stat['closed'];
        }

    }

    private function format( $date ) {
        return $date->format( 'd-m-Y' );
    }

    private function graph_data() { ?>

        <script>

            jQuery(document).ready( function () {

                new Chartist.Line('#stats-chart', {
                    labels: <?php echo wp_json_encode( array_keys( $this->opened_tickets ) ); ?>,
                    series: [{
                        name: 'opened-tickets',
                        data: <?php echo wp_json_encode( array_values( $this->opened_tickets ) ); ?>
                    }, {
                        name: 'closed-tickets',
                        data: <?php echo wp_json_encode( array_values( $this->closed_tickets ) ); ?>
                    }]
                }, {
                    fullWidth: true,
                    series: {
                        'opened-tickets': {
                            lineSmooth: Chartist.Interpolation.simple(),
                            showArea: true
                        },
                        'closed-tickets': {
                            lineSmooth: Chartist.Interpolation.simple(),
                            showArea: true
                        }
                    },

                    axisY: {
                        onlyInteger: true
                    }
                });

            });

        </script>

        <div class="stats-chart-wrapper"><div id="stats-chart" class="ct-chart ct-golden-section"></div></div>

    <?php }

    public function render() { ?>

        <div class="stats-overview stat-section">

            <form method="post">

                <div class="stats-header">

                    <div class="form-inline">

                        <div class="control-group">

                            <select name="date_range" class="date-range-select form-control">

                                <?php foreach( $this->date_range_options as $option => $label ) : ?>

                                    <option value="<?php echo $option; ?>"
                                        <?php selected( $option, isset( $_POST['date_range'] ) ? $_POST['date_range'] : '' ); ?>><?php echo $label; ?></option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="date-range control-group <?php echo isset( $_POST['date_range'] ) && $_POST['date_range'] == 'custom' ? '' : 'hidden'; ?>">

                            <input name="start_date" class="date start-date" type="text" value="<?php echo $this->format( $this->start ); ?>" />

                            <span><?php _e( 'to', \SmartcatSupport\PLUGIN_ID ); ?></span>

                            <input name="end_date" class="date end-date" type="text" value="<?php echo $this->format( $this->end ); ?>"/>

                        </div>

                        <div class="control-group">

                            <button type="submit" class="form-control button button-secondary"><?php _e( 'Go', \SmartcatSupport\PLUGIN_ID ); ?></button>

                        </div>

                    </div>

                </div>

                <?php $this->graph_data(); ?>

            </form>

        </div>

    <?php }
}