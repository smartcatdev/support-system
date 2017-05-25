<?php

namespace ucare\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $date;

    private $predefined_ranges;

    public function __construct() {

        parent::__construct( array(
            'slug'  => 'overview',
            'title' => __( 'Overview', \ucare\PLUGIN_ID )
        ) );

        $this->predefined_ranges = array(
            'last_week'     => __( 'Last 7 Days', \ucare\PLUGIN_ID ),
            'this_month'    => __( 'This Month', \ucare\PLUGIN_ID ),
            'last_month'    => __( 'Last Month', \ucare\PLUGIN_ID ),
            'this_year'     => __( 'This Year', \ucare\PLUGIN_ID ),
            'custom'        => __( 'Custom', \ucare\PLUGIN_ID ),
        );

        $this->date = new \DateTimeImmutable();
    }

    private function default_start() {
        return $this->date->sub( new \DateInterval( 'P7D' ) );
    }

    private function date_range() {
        $dates = array();

        if( isset( $_REQUEST['overview_date_nonce'] ) &&
            wp_verify_nonce( $_REQUEST['overview_date_nonce'],'overview_date_range' ) ) {

            $dates['start'] = date_create(
                ( isset($_GET['start_year']) ? $_GET['start_year'] : '' ) . '-' .
                ( isset($_GET['start_month']) ? $_GET['start_month'] : '' ) . '-' .
                ( isset($_GET['start_day']) ? $_GET['start_day'] : '' )
            );

            $dates['end'] = date_create(
                ( isset($_GET['end_year'] ) ? $_GET['end_year'] : '' ) . '-' .
                ( isset($_GET['end_month'] ) ? $_GET['end_month'] : '' ) . '-' .
                ( isset($_GET['end_day'] ) ? $_GET['end_day'] : '' )
            );

        }

        if( empty( $dates ) || !$dates['start'] || !$dates['end'] ) {
            $dates['start'] = $this->default_start();
            $dates['end'] = $this->date;
        }

        return $dates;
    }

    private function graph_data( $data ) { ?>

        <script>

            jQuery(document).ready(function ($) {

                const totals = <?php echo json_encode($data); ?>;

                var dates = Object.keys(totals);
                var start = moment(dates[0]);
                var end = moment(dates[dates.length - 1]);
                var diff = end.diff(start, 'days');
                var scale = [1, 'day'];

                if(diff > 14 && diff < 1095) {
                    scale = [diff / 4, 'day']
                }

                var opened = [];
                var closed = [];

                Object.keys(totals).forEach(function(key) {
                    var time = new Date(key).getTime();
                    opened.push([ time, totals[key].opened ]);
                    closed.push([ time, totals[key].closed ]);
                });

                $.plot('#ticket-overview-chart', [
                    { label: 'Opened', data: opened },
                    { label: 'Closed', data: closed }
                ],{
                    series: {
                        lines: { show: true },
                        points: { show: true }
                    },
                    xaxis: {
                        mode: 'time',
                        minTickSize: scale,
                        tickFormatter: function (val, axis) {
                            return (axis.delta / 100) > 5184000 ? moment(val).format('YYYY MMM') : moment(val).format('MMM DD')
                        },
                    },
                    yaxis: {
                        min: 0,
                        minTickSize: 1,
                        position: 'right',
                        tickDecimals: 0
                    }
                });

            });

        </script>

        <div class="stats-chart-wrapper"><div id="ticket-overview-chart" style="width:100%;height:300px"></div></div>

    <?php }

    public function render() { ?>

        <div class="stats-page-wrapper">

            <form method="get">

                <div class="date-range-form">

                        <input type="hidden" name="page" value="<?php echo $this->page; ?>" />
                        <input type="hidden" name="tab" value="<?php echo $this->slug; ?>" />

                        <?php wp_nonce_field( 'overview_date_range', 'overview_date_nonce', false ); ?>

                        <div class="form-inline">
                            <div class="control-group">
                                <select name="range" class="date-range-select form-control">

                                    <?php foreach ( $this->predefined_ranges as $option => $label ) : ?>

                                        <option value="<?php echo $option; ?>"
                                            <?php selected( $option, isset( $_GET['range'] ) ? $_GET['range'] : '' ); ?>>

                                            <?php echo $label; ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>
                            </div>
                            <div class="date-range control-group <?php echo isset( $_GET['range'] ) && $_GET['range'] == 'custom' ? '' : 'hidden'; ?>">
                                <span class="start_date">
                                    <?php

                                        $default = $this->default_start();

                                        $this->date_picker(
                                            'start_',
                                            $default->format( 'n' ),
                                            $default->format( 'j' ),
                                            $default->format( 'Y' )
                                        );

                                    ?>
                                </span>
                                <span>—</span>
                                <span class="end_date">
                                    <?php

                                        $this->date_picker(
                                            'end_',
                                            $this->date->format( 'n' ),
                                            $this->date->format( 'j' ),
                                            $this->date->format( 'Y' )
                                        );

                                    ?>
                                </span>
                            </div>
                            <div class="control-group">
                                <button type="submit" class="form-control button button-secondary"><?php _e( 'Go', \ucare\PLUGIN_ID ); ?></button>
                            </div>
                        </div>

                </div>
                <div class="stats-graph stats-section">

                    <?php

                        $range = $this->date_range();

                        $this->graph_data( \ucare\statprocs\count_tickets( $range['start'], $range['end'] ) );

                    ?>

                </div>

                <?php

                    $totals = new AgentStatsTable( $range['start'], $range['end'] );

                    $totals->prepare_items();
                    $totals->display();

                ?>

            </form>

        </div>

    <?php }

    private function date_picker( $prefix = '', $month = '', $day = '', $year = '' ) { ?>

        <select name="<?php echo $prefix; ?>month">

            <?php for( $m = 1; $m <= 12; $m++ ) : ?>

                <option value="<?php echo $m; ?>"

                    <?php selected( isset( $_GET["{$prefix}month"] ) ? $_GET["{$prefix}month"] : $month, $m ); ?>>

                    <?php _e( date('F', mktime(0, 0, 0, $m, 1 ) ), \ucare\PLUGIN_ID ); ?>

                </option>

            <?php endfor; ?>

        </select>

        <select name="<?php echo $prefix; ?>day">

            <?php for( $d = 1; $d <= 31; $d++ ) : ?>

                <option value="<?php echo $d; ?>"

                    <?php selected( isset( $_GET["{$prefix}day"] ) ? $_GET["{$prefix}day"] : $day, $d ); ?>><?php echo $d; ?></option>

            <?php endfor; ?>

        </select>

        <?php $this_year = $this->date->format( 'Y' ); ?>

        <select name="<?php echo $prefix; ?>year">

            <?php for( $y = $this_year; $y >= $this_year - 10; $y-- ) : ?>

                <option value="<?php echo $y; ?>"

                    <?php selected( isset( $_GET["{$prefix}year"] ) ? $_GET["{$prefix}year"] : $year, $y ); ?>><?php echo $y; ?></option>

            <?php endfor; ?>

        </select>

    <?php }

}