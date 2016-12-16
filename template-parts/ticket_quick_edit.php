<?php

use const SmartcatSupport\TEXT_DOMAIN;

?>

<fieldset class="inline-edit-col-left"><div class="inline-edit-col">
    <legend class="inline-edit-legend"><?php _e( 'Ticket Details', TEXT_DOMAIN ); ?></legend>

    <div class="inline-edit-group">

        <?php foreach( $form->fields as $field ) : ?>

            <label for="<?php echo $field->id; ?>"><span class="title"><?php _e( $field->label, TEXT_DOMAIN ); ?></span>

                <?php $field->render(); ?>

            </label>

        <?php endforeach; ?>

        <input type="hidden" name="<?php esc_attr_e( $form->id ); ?>"/>

    </div>

</fieldset>