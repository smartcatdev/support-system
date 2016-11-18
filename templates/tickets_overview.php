<?php

use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="tickets_overview">

    <?php if( !empty( $data ) ) : ?>

        <form id="ticket_filter">

            <input type="hidden" name="<?php esc_attr_e( $form->get_id() ); ?>" />

            <?php foreach( $form->get_fields() as $field ) : ?>

                <?php $field->render(); ?>

            <?php endforeach; ?>

            <input type="submit" value="<?php _e( 'Filter', TEXT_DOMAIN ); ?>" />

        </form>

        <?php include 'tickets_table.php'; ?>

    <?php else: ?>

        <div class="message">

            <p><?php _e( get_option( Option::EMPTY_TABLE_MSG, Option\Defaults::EMPTY_TABLE_MSG ) ); ?></p>

        </div>

    <?php endif; ?>

</div>


