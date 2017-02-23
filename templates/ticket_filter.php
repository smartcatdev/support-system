<?php

use SmartcatSupport\Plugin;

$form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/ticket_filter.php';

?>

<div id="filter-controls" class="row">

    <div class="row-table">

    <div class="row-table-cell additional-filters">

            <button type="button" id="show-filters" class="btn btn-default">

                <span style="line-height: 20px" class="indicator"></span>

            </button>

        </div>

        <div class="row-table-cell search">

            <div class="search input-group">

                <input id="search"
                       name="search"
                       data-default=""
                       placeholder="<?php _e('Search', \SmartcatSupport\PLUGIN_ID); ?>"
                       class="form-control filter-field"/>

                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>

            </div>

        </div>

        <div class="row-table-cell pull-right">

            <div class="filter-controls btn-group input-group">

                <button type="button" class="btn btn-default" id="filter-toggle">

                    <span style="line-height: 20px" class=" filter glyphicon glyphicon-filter"></span>

                </button>

                <button type="button" class="btn btn-default" id="refresh-tickets">

                    <span style="line-height: 20px" class="refresh glyphicon glyphicon-refresh"></span>

                </button>

            </div>

        </div>

    </div>

    <div id="filters">

        <div class="row">

            <form id="ticket_filter form-horizontal">

                <?php foreach ( $form->fields as $name => $field ) : ?>

                    <div class="form-group">

                        <div class="col-sm-2">

                            <label class="control-label" for="<?php echo $field->id; ?>"><?php echo $field->label; ?></label>

                        </div>

                        <div class="col-sm-4">

                            <?php $field->render(); ?>

                        </div>

                        <div class="clearfix"></div>

                    </div>

                <?php endforeach; ?>

                <input type="hidden" name="<?php echo $form->id; ?>"/>

            </form>

        </div>

    </div>

</div>
