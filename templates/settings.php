<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

$form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/settings_form.php';

?>

<div id="settings">

    <form id="settings-form">

        <?php foreach( $form->fields as $name => $field ) : ?>

            <div class="form-group <?php echo $name == "confirm_password" ? "has-feedback" : ""; ?>">

                <label for="<?php echo $field->id; ?>"><?php echo $field->label; ?></label>

                <?php $field->render(); ?>

            </div>

        <?php endforeach; ?>

        <input type="hidden" name="<?php echo $form->id; ?>" />

        <div class="row">

            <div class="bottom col-sm-12">

                <button type="submit" class="button button-submit">

                    <?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?>

                </button>

            </div>

        </div>

    </form>

</div>
