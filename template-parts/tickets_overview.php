<?php

use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use const SmartcatSupport\PLUGIN_NAME;

?>

<div id="tickets_overview">

    <?php if( !empty( $data ) ) : ?>

        <form id="ticket_filter">

            <?php $products = get_products(); if( $products ) : ?>

                <?php ( new SelectBoxField(
                    array(
                        'id'        => 'product',
                        'options'   =>  array( '' => __( 'All Products', PLUGIN_NAME ) ) + $products
                    )
                ) )->render(); ?>

            <?php endif; ?>

            <?php ( new SelectBoxField(
                array(
                    'id'        => 'status',
                    'options'   =>  array( '' => __( 'Any Status', PLUGIN_NAME ) ) + get_option( Option::STATUSES, Option\Defaults::STATUSES )
                )
            ) )->render(); ?>

            <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

                <?php ( new SelectBoxField(
                    array(
                        'id'        => 'agent',
                        'options'   =>  array( '' => __( 'All Agents', PLUGIN_NAME ) ) + get_agents()
                    )
                ) )->render(); ?>

            <?php endif; ?>


            <span class="trigger" data-action="filter_tickets"><i class="filter icon-filter"></i><?php _e( 'Filter', PLUGIN_NAME ); ?></span>
            <span class="trigger" data-action="refresh_tickets"><i class="refresh icon-loop2"></i><?php _e( 'Refresh', PLUGIN_NAME ); ?></span>

        </form>

        <?php include 'tickets_table.php'; ?>

    <?php else: ?>

        <div class="message">

            <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ) ); ?></p>

        </div>

    <?php endif; ?>

</div>


