<?php

use smartcat\form\Form;
use SmartcatSupport\Plugin;
use const SmartcatSupport\PLUGIN_ID;

$form = include_once Plugin::plugin_dir( PLUGIN_ID ) . '/config/settings_form.php';

?>

<div class="settings">

    <form>

        <?php Form::render_fields( $form ); ?>

    </form>

</div>