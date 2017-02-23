<?php

use SmartcatSupport\Plugin;

$form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/ticket_filter.php';

?>


    <div class="row-fluid">

            <div class="col-xs-10">

                 <div class="search input-group">

                    <input id="#search"
                           name="search"
                           data-default=""
                           placeholder="<?php _e( 'Search', \SmartcatSupport\PLUGIN_ID ); ?>"
                           class="form-control filter-field" />

                    <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>

                </div>

            </div>

                <div class="col-xs-2">



                <div class="filter-controls btn-group input-group">

                    <button type="button" class="btn btn-default" id="filter-toggle">

                        <span style="line-height: 20px"
                              class=" filter glyphicon glyphicon-filter"></span>

                    </button>

                    <button type="button" class="btn btn-default" id="refresh-tickets">

                        <span style="line-height: 20px"
                              class="refresh glyphicon glyphicon-refresh"></span>

                    </button>

                </div>

            </div>



    <div class="col-sm-12">

    <form id="ticket_filter" class="form-inline">

        <?php foreach ( $form->fields as $name => $field ) : ?>

            <div class="form-group">

                <?php $field->render(); ?>

            </div>

        <?php endforeach; ?>

        <input type="hidden" name="<?php echo $form->id; ?>"/>

        <?php do_action( 'support_tickets_table_filters' ); ?>



    </form>


</div>
