<?php

use smartcat\form\SelectBoxAbstractField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div id="tickets_overview">

    <?php if( !empty( $data ) ) : ?>

        <form id="ticket_filter">

            <?php $products = get_products(); if( $products ) : ?>

                <?php ( new SelectBoxAbstractField(
                    array(
                        'id'        => 'product',
                        'options'   =>  array( '' => __( 'All Products', TEXT_DOMAIN ) ) + $products
                    )
                ) )->render(); ?>

            <?php endif; ?>

            <?php ( new SelectBoxAbstractField(
                array(
                    'id'        => 'status',
                    'options'   =>  array( '' => __( 'Any Status', TEXT_DOMAIN ) ) + get_option( Option::STATUSES, Option\Defaults::STATUSES )
                )
            ) )->render(); ?>

            <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

                <?php ( new SelectBoxAbstractField(
                    array(
                        'id'        => 'agent',
                        'options'   =>  array( '' => __( 'All Agents', TEXT_DOMAIN ) ) + get_agents()
                    )
                ) )->render(); ?>

            <?php endif; ?>


            <span class="trigger" data-action="filter_tickets"><i class="filter icon-filter"></i><?php _e( 'Filter', TEXT_DOMAIN ); ?></span>
            <span class="trigger" data-action="refresh_tickets"><i class="refresh icon-loop2"></i><?php _e( 'Refresh', TEXT_DOMAIN ); ?></span>

        </form>

        <?php include 'tickets_table.php'; ?>

    <?php else: ?>

        <div class="message">

            <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ) ); ?></p>

        </div>

    <?php endif; ?>

</div>


