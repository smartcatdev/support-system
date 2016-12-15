<?php

use const SmartcatSupport\TEXT_DOMAIN;

?>

<fieldset class="inline-edit-col-left"><div class="inline-edit-col">
    <legend class="inline-edit-legend"><?php _e( 'Ticket Details', TEXT_DOMAIN ); ?></legend>

    <div class="inline-edit-group">

        <?php foreach( $form->get_fields() as $field ) : ?>

            <label for="<?php echo $field->get_id(); ?>"><span class="title"><?php _e( $field->get_label(), TEXT_DOMAIN ); ?></span>

                <?php $field->render(); ?>

            </label>

        <?php endforeach; ?>

        <input type="hidden" name="<?php esc_attr_e( $form->get_id() ); ?>"/>

    </div>

</fieldset>