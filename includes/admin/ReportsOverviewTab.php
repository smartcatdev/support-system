<?php

namespace SmartcatSupport\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $year;

    private $date_range_options;

    private $opened_tickets = array();
    private $closed_tickets = array();
    private $labels = array();

    public function __construct() {

        parent::__construct( array( 'title' => __( 'Overview', \SmartcatSupport\PLUGIN_ID ) ) );

        $this->date_range_options = array(
            'last_week'     => __( 'Last 7 Days', \SmartcatSupport\PLUGIN_ID ),
            'this_month'    => __( 'This Month', \SmartcatSupport\PLUGIN_ID ),
            'last_month'    => __( 'Last Month', \SmartcatSupport\PLUGIN_ID ),
            'this_year'     => __( 'This Year', \SmartcatSupport\PLUGIN_ID ),
            'custom'        => __( 'Custom', \SmartcatSupport\PLUGIN_ID ),
        );

        $this->year = date( 'Y' );

        \SmartcatSupport\statprocs\count_tickets( new \DateTime( '2017-01-01' ), new \DateTime( '2017-05-05' ));
    }

    private function init() {
        $tz = new \DateTimeZone( !empty( $zone = get_option( 'timezone_string' ) ) ? $zone : 'UTC' );

        if( isset( $_GET['start_date'] ) && isset( $_GET['end_date'] ) ) {
            $start = new \DateTime( $_GET['start_date'], $tz );
            $end = new \DateTime( $_GET['end_date'], $tz );

            $min = new \DateTime( ( new \DateTime( '-2 years', $tz ) )->format( 'd-m-Y' ), $tz );
            $max = new \DateTime( ( new \DateTime( 'now', $tz ) )->format( 'd-m-Y' ), $tz );

            $this->start = $start < $end && $start >= $min && $start <= $max ? $start : new \DateTime( '7 days ago', $tz );
            $this->end = $end > $start && $end >= $min && $end < $max ? $end : new \DateTime( 'now', $tz );
        } else {
            $this->start = new \DateTime( '7 days ago', $tz );
            $this->end = new \DateTime( 'now', $tz );
        }

        $ticket_stats = \SmartcatSupport\statprocs\tickets_overview_by_range( $this->start, $this->end );

        foreach( $ticket_stats['data'] as $stat ) {
            $this->labels[] = $stat['date']->format( 'Y-m-d' );
            $this->opened_tickets[] = array( 'meta' =>  $stat['date']->format( 'D M Y' ), 'value' => $stat['opened'] );
            $this->closed_tickets[] = array( 'meta' =>  $stat['date']->format( 'D M Y' ), 'value' => $stat['closed'] );
        }


    }

    private function graph_data() { ?>

        <script>

            jQuery(document).ready( function () {

                var chart = new Chartist.Line('#ticket-overview-chart', {
                    labels: <?php echo wp_json_encode( $this->labels ); ?>,
                    series: [{
                        name: 'opened-tickets',
                        data: <?php echo wp_json_encode( array_values( $this->opened_tickets ) ); ?>
                    }, {
                        name: 'closed-tickets',
                        data: <?php echo wp_json_encode( array_values( $this->closed_tickets ) ); ?>
                    }]
                }, {
                    margin: {
                        right: '30px'
                    },
                    fullWidth: true,
                    series: {
                        'opened-tickets': {
                            lineSmooth: false,
                            showArea: true
                        },
                        'closed-tickets': {
                            lineSmooth: false
                        }
                    },
                    axisY: {
                        onlyInteger: true
                    },
                    axisX: {
                        labelInterpolationFnc: function(value, index, labels) {

                            if(labels.length < 28) {
                                value = moment(value).format('MMM D');
                            } else if(labels.length < 32) {
                                value = index % 2 === 0 ? moment(value).format('MMM D') : null;
                            } else {
                                value = moment(value).format('MMM');
                            }

                            return value;
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

                chart.on('created', function(context) {
                    context.svg.elem('rect', {
                        x: context.chartRect.x1,
                        y: context.chartRect.y2,
                        width: context.chartRect.width(),
                        height: context.chartRect.height(),
                        fill: 'none',
                        stroke: '#e5e5e5',
                        'stroke-width': '1px'
                    })
                });

            });

        </script>

        <div class="stats-chart-wrapper"><div id="ticket-overview-chart" class="ct-chart ct-golden-section"></div></div>

    <?php }

    public function render() { ?>

        <div class="stats-overview stat-section">
            <form method="get">
                <input type="hidden" name="page" value="<?php echo $this->page; ?>" />
                <input type="hidden" name="tab" value="<?php echo $this->slug; ?>" />
                <div class="stats-header">
                    <div class="form-inline">
                        <div class="control-group">
                            <select name="range" class="date-range-select form-control">

                                <?php foreach( $this->date_range_options as $option => $label ) : ?>

                                    <option value="<?php echo $option; ?>"
                                        <?php selected( $option, isset( $_GET['range'] ) ? $_GET['range'] : '' ); ?>>

                                        <?php echo $label; ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>
                        <div class="date-range control-group <?php echo isset( $_GET['range'] ) && $_GET['range'] == 'custom' ? '' : 'hidden'; ?>">
                            <span><?php _e( 'From', \SmartcatSupport\PLUGIN_ID ); ?></span>
                            <span class="start_date">
                                <?php $this->date_picker(
                                    'start_',
                                    isset( $_GET['start_month'] ) ? $_GET['start_month'] : '',
                                    isset( $_GET['start_day'] ) ? $_GET['start_day'] : '',
                                    isset( $_GET['start_year'] ) ? $_GET['start_year'] : ''
                                ); ?>
                            </span>
                            <span><?php _e( 'to', \SmartcatSupport\PLUGIN_ID ); ?></span>
                            <span class="end_date">
                                <?php $this->date_picker(
                                    'end_',
                                    isset( $_GET['end_month'] ) ? $_GET['end_month'] : '',
                                    isset( $_GET['end_day'] ) ? $_GET['end_day'] : '',
                                    isset( $_GET['end_year'] ) ? $_GET['end_year'] : ''
                                ); ?>
                            </span>
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

    private function date_picker( $prefix = '', $month = '', $day = '', $year = '' ) { ?>

        <select name="<?php echo $prefix; ?>month">

            <?php for( $m = 1; $m <= 12; $m++ ) : ?>

                <option <?php selected( $m, $month ); ?>
                    value="<?php echo $m; ?>">

                    <?php _e( date('F', mktime(0, 0, 0, $m, 1 ) ), \SmartcatSupport\PLUGIN_ID ); ?>

                </option>

            <?php endfor; ?>

        </select>

        <select name="<?php echo $prefix; ?>day">

            <?php for( $d = 1; $d <= 31; $d++ ) : ?>

                <option <?php selected( $d, $day ); ?>value="<?php echo $d; ?>"><?php echo $d; ?></option>

            <?php endfor; ?>

        </select>

        <select name="<?php echo $prefix; ?>year">

            <?php for( $y = $this->year; $y >= $this->year - 10; $y-- ) : ?>

                <option <?php selected( $y, $year ); ?>value="<?php echo $y; ?>"><?php echo $y; ?></option>

            <?php endfor; ?>

        </select>

    <?php }

}