<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $date;

    private $predefined_ranges;

    public function __construct() {

        parent::__construct( array( 'title' => __( 'Overview', \SmartcatSupport\PLUGIN_ID ) ) );

        $this->predefined_ranges = array(
            'last_week'     => __( 'Last 7 Days', \SmartcatSupport\PLUGIN_ID ),
            'this_month'    => __( 'This Month', \SmartcatSupport\PLUGIN_ID ),
            'last_month'    => __( 'Last Month', \SmartcatSupport\PLUGIN_ID ),
            'this_year'     => __( 'This Year', \SmartcatSupport\PLUGIN_ID ),
            'custom'        => __( 'Custom', \SmartcatSupport\PLUGIN_ID ),
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

                var totals = <?php echo json_encode( $data ); ?>;

                var data = {
                    series: [
                        $.map(totals, function(el, index) {
                            var date = moment(index);

                            return { y: el.opened, x: date, meta: el.opened + ' Opened, ' + date.format('MMM D YYYY') }
                        }),
                        $.map(totals, function(el, index) {
                            var date = moment(index);

                            return { y: el.closed, x: date, meta: el.closed + ' Closed, ' + date.format('MMM D YYYY') }
                        })
                    ]
                };

                var chart = new Chartist.Line('#ticket-overview-chart', data, {
                    showArea: true,
                    showLine: false,
                    fullWidth: true,
                    axisY: {
                        onlyInteger: true
                    },
                    axisX: {
                        type: Chartist.AutoScaleAxis,
                        scaleMinSpace: 60,
                        labelInterpolationFnc: function(value, index) {
                            if(Object.keys(totals).length > 365) {
                                value = moment(value).format('MMM YYYY');
                            } else {
                                value = moment(value).format('MMM D')
                            }

                            return value
                        }
                    },
                    plugins: [
                        Chartist.plugins.tooltip({
                            appendToBody: true
                        }),
                        Chartist.plugins.legend({
                            legendNames: ['Opened', 'Closed']
                        })
                    ]
                });

                chart.on('draw', function(context) {
                    if(context.type === 'line' || context.type === 'area') {
                        context.element.animate({
                            d: {
                                begin: 2000 * context.index,
                                dur: 2000,
                                from: context.path.clone().scale(1, 0).translate(0, context.chartRect.height()).stringify(),
                                to: context.path.clone().stringify(),
                                easing: Chartist.Svg.Easing.easeOutQuint
                            }
                        });
                    }
                });

            });

        </script>

        <div class="stats-chart-wrapper"><div id="ticket-overview-chart" class="ct-chart ct-golden-section"></div></div>

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

                                    <?php foreach($this->predefined_ranges as $option => $label ) : ?>

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
                                <button type="submit" class="form-control button button-secondary"><?php _e( 'Go', \SmartcatSupport\PLUGIN_ID ); ?></button>
                            </div>
                        </div>

                </div>
                <div class="stats-graph stats-section">

                    <?php

                        $range = $this->date_range();

                        $this->graph_data( \SmartcatSupport\statprocs\count_tickets( $range['start'], $range['end'] ) );

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

                    <?php _e( date('F', mktime(0, 0, 0, $m, 1 ) ), \SmartcatSupport\PLUGIN_ID ); ?>

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