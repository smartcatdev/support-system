<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\form\Form;

?>

<div class="tickets_overview">

    <?php if( !empty( $data ) ) : ?>

        <form id="ticket_filter">

            <?php Form::form_fields( $filter_form ); ?>

            <span class="trigger filter icon-filter" data-action="filter_tickets"></span>
            <span class="trigger refresh icon-loop2" data-action="refresh_tickets"></span>

        </form>

        <?php include 'tickets_table.php'; ?>

    <?php else: ?>

        <div class="message">

            <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ) ); ?></p>

        </div>

    <?php endif; ?>

</div>


