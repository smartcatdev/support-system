<?php

namespace ucare\admin;

use smartcat\admin\MenuPageTab;

class ReportsOverviewTab extends MenuPageTab {

    private $predefined_ranges;

    private $start_date = '';
    private $end_date = '';

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

        $this->date_range();
    }

    private function date_range() {
        if( isset( $_REQUEST['overview_date_nonce'] ) &&
            wp_verify_nonce( $_REQUEST['overview_date_nonce'],'overview_date_range' ) ) {

            $this->start_date = new \DateTime( $_GET['start_year']  . '-' . $_GET['start_month'] . '-' . $_GET['start_day'] );
            $this->end_date   = new \DateTime( $_GET['end_year']    . '-' . $_GET['end_month']   . '-' . $_GET['end_day'] );

        } else {

            $this->start_date = date_create()->sub( new \DateInterval( 'P7D' ) );
            $this->end_date = date_create();

        }
    }

    private function graph_data() {

        $opened = \ucare\statprocs\count_tickets( $this->start_date, $this->end_date );
        $closed = \ucare\statprocs\count_tickets( $this->start_date, $this->end_date, array( 'closed' => true ) );

        ?><script>

            jQuery(document).ready(function ($) {

                var opened = format_data(<?php echo json_encode( $opened ); ?>);
                var closed = format_data(<?php echo json_encode( $closed ); ?>);

                function format_data(set) {
                    var totals = [];

                    Object.keys(set).forEach(function(date) {
                        totals.push([ new Date(date).getTime(), set[ date ] ]);
                    });

                    return totals;
                }

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
                        minTickSize: [1, 'day']
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

                                        $date = date_create();
                                        $default = $date->sub( new \DateInterval( 'P7D' ) );

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
                                            $date->format( 'n' ),
                                            $date->format( 'j' ),
                                            $date->format( 'Y' )
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

                    <?php $this->graph_data(); ?>

                </div>

                <?php

                    $totals = new AgentStatsTable( $this->start_date, $this->end_date );

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

        <?php $this_year = date_create()->format( 'Y' ); ?>

        <select name="<?php echo $prefix; ?>year">

            <?php for( $y = $this_year; $y >= $this_year - 10; $y-- ) : ?>

                <option value="<?php echo $y; ?>"

                    <?php selected( isset( $_GET["{$prefix}year"] ) ? $_GET["{$prefix}year"] : $year, $y ); ?>><?php echo $y; ?></option>

            <?php endfor; ?>

        </select>

    <?php }

}
