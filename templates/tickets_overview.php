<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\field\SelectBox;
use SmartcatSupport\form\Form;
use function SmartcatSupport\get_agents;
use function SmartcatSupport\get_products;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div id="tickets_overview">

    <?php if( !empty( $data ) ) : ?>

        <form id="ticket_filter">

            <span class="trigger filter icon-filter" data-action="filter_tickets"></span>
            <span class="trigger refresh icon-loop2" data-action="refresh_tickets"></span>

            <?php //Setup ticket filter select boxes ?>

            <?php ( new SelectBox( 'product', array(
                'options' =>  array( '' => __( 'Product', TEXT_DOMAIN ) ) + get_products() ) ) )->render(); ?>

            <?php ( new SelectBox( 'status', array(
                'options' =>  array( '' => __( 'Status', TEXT_DOMAIN ) ) + get_option( Option::STATUSES, Option\Defaults::STATUSES ) ) ) )->render(); ?>

            <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

                <?php ( new SelectBox( 'agent', array(
                    'options' =>  array( '' => __( 'Agent', TEXT_DOMAIN ) ) + get_agents() ) ) )->render(); ?>

            <?php endif; ?>

        </form>

        <?php include 'tickets_table.php'; ?>

    <?php else: ?>

        <div class="message">

            <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ) ); ?></p>

        </div>

    <?php endif; ?>

</div>


