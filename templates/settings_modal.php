<?php

use smartcat\form\Form;
use SmartcatSupport\Plugin;

$form = include_once Plugin::plugin_dir( Plugin::ID ) . '/config/settings_form.php';

?>

<div class="support_settings">

    <form class="settings_form" data-action="support_save_settings" data-before="validate_settings">

        <?php Form::render_fields( $form ); ?>

        <input type="submit" value="<?php _e( 'Save Settings', Plugin::ID ); ?>" />

        <p class="status hidden"></p>

    </form>

</div>