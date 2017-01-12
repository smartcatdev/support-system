<?php

use smartcat\form\Form;
use smartcat\form\TextBoxField;
use const SmartcatSupport\PLUGIN_ID;

$form = new Form( 'support_settings' );

$form->add_field( new TextBoxField(
    array(
        'id'                => 'password',
        'type'              => 'password',
        'label'             => __( 'New Password', PLUGIN_ID )
    )
) );

return $form;