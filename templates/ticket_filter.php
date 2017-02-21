<?php

use SmartcatSupport\Plugin;

$form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/ticket_filter.php';

?>

<div class="row">

    <form id="ticket_filter" class="form-inline">

        <?php foreach ( $form->fields as $name => $field ) : ?>

            <div class="form-group">

                <?php $field->render(); ?>

            </div>

        <?php endforeach; ?>

        <input type="hidden" name="<?php echo $form->id; ?>"/>

        <?php do_action( 'support_tickets_table_filters' ); ?>

        <div style="margin-left: 4px" class="btn-group pull-right">

            <button type="button" class="btn btn-default" id="filter-toggle">

                <span style="line-height: 20px"
                      class=" filter glyphicon glyphicon-filter"></span><?php //_e( 'Filter', \SmartcatSupport\PLUGIN_ID ); ?>

            </button>

            <button type="button" class="btn btn-default" id="refresh-tickets">

                <span style="line-height: 20px"
                      class="refresh glyphicon glyphicon-refresh"></span><?php //_e( 'Refresh', \SmartcatSupport\PLUGIN_ID ); ?>

            </button>

        </div>

    </form>

</div>
