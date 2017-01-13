<?php

use smartcat\form\Form;
use SmartcatSupport\Plugin;
use const SmartcatSupport\PLUGIN_ID;

$form = include_once Plugin::plugin_dir( PLUGIN_ID ) . '/config/settings_form.php';

?>

<div class="support_settings">

    <form class="settings_form" data-action="support_save_settings" data-before="validate_settings">

        <?php Form::render_fields( $form ); ?>

        <input type="submit" value="<?php _e( 'Save Settings', PLUGIN_ID ); ?>" />

    </form>

</div>