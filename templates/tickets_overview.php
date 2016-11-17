<?php use const SmartcatSupport\TEXT_DOMAIN; ?>

<div class="support_card">

    <button class="trigger"
        data-action="create_ticket"
        data-after="refresh_ticket"><?php _e( 'Create Ticket', TEXT_DOMAIN ); ?>
    </button>

    <form data-action="support_filter_tickets"
        data-after="replace_table">

        <input type="hidden" name="<?php esc_attr_e( $form->get_id() ); ?>" />

        <?php foreach( $form->get_fields() as $field ) : ?>

            <?php $field->render(); ?>

        <?php endforeach; ?>

        <div class="button_wrapper">

            <button class="button submit">

                <div class="status hidden"></div>

                <span class="text"
                      data-default="<?php _e( 'Filter', TEXT_DOMAIN ); ?>"
                      data-success="<?php _e( 'Filter', TEXT_DOMAIN ); ?>"
                      data-fail="<?php _e( 'Error', TEXT_DOMAIN ); ?>"
                      data-wait="<?php _e( 'Working', TEXT_DOMAIN ); ?>">

                          <?php _e( 'Filter', TEXT_DOMAIN ); ?>

                    </span>

            </button>

        </div>

    </form>

    <?php include 'tickets_table.php'; ?>

</div>


