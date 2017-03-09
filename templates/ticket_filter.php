<?php

use SmartcatSupport\Plugin;

$form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/ticket_filter.php';

?>

<div id="filter-controls" class="row" style="display: none">

    <div class="row-table">

        <div class="row-table-cell search">

            <div id="search-wrapper" class="input-group">

                <span class="input-group-btn">

                    <button type="button" id="show-filters" class="btn btn-default">

                        <span class="glyphicon glyphicon-filter"></span>

                        <span class="toggle-label"><?php _e( 'Filter By', \SmartcatSupport\PLUGIN_ID ); ?></span>

                    </button>

                </span>

                <input id="search"
                       name="search"
                       type="text"
                       data-default=""
                       placeholder="<?php _e('Search', \SmartcatSupport\PLUGIN_ID); ?>"
                       class="form-control filter-field"/>

                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>

            </div>

        </div>

        <div class="row-table-cell pull-right">

            <div id="filter-actions" class="btn-group input-group">

                <button style="line-height: 22px" type="button" class="btn btn-default" id="filter-toggle">

                    <span class="toggle-label"></span>

                </button>

                <button type="button" class="btn btn-default" id="refresh-tickets">

                    <span style="line-height: 22px" class="refresh glyphicon glyphicon-refresh"></span>

                </button>

            </div>

        </div>

    </div>

    <div id="filters" style="display: none">

        <div class="row">

            <form id="ticket_filter" class="form-horizontal">

                <?php foreach ( $form->fields as $name => $field ) : ?>

                    <div class="form-group">

                        <div class="col-sm-2 text-left">

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
