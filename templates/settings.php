<?php

use smartcat\form\Form;
use ucare\descriptor\Option;
use ucare\Plugin;

$form = include_once Plugin::plugin_dir( \ucare\PLUGIN_ID ) . '/config/settings_form.php';

?>

<div id="settings">

    <form id="settings-form">

        <?php foreach( $form->fields as $name => $field ) : ?>

            <div class="form-group <?php echo $name == "confirm_password" ? "has-feedback" : ""; ?>">

                <label for="<?php echo $field->id; ?>"><?php echo $field->label; ?></label>

                <?php $field->render(); ?>

                <p class="description"><?php echo $field->desc; ?></p>

            </div>

        <?php endforeach; ?>

        <input type="hidden" name="<?php echo $form->id; ?>" />

    </form>

</div>
