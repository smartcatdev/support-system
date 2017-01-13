<?php

use smartcat\form\Form;
use smartcat\form\TextBoxField;
use const SmartcatSupport\PLUGIN_ID;

$form = new Form( 'support_settings' );

$form->add_field( new TextBoxField(
    array(
        'id'        => 'firstname',
        'label'     => __( 'First Name', PLUGIN_ID ),
        'value'     => wp_get_current_user()->first_name
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'lastname',
        'label'     => __( 'Last Name', PLUGIN_ID ),
        'value'     => wp_get_current_user()->last_name
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'new_password',
        'type'      => 'password',
        'label'     => __( 'New Password', PLUGIN_ID )
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'confirm_password',
        'type'      => 'password',
        'label'     => __( 'Confirm Password', PLUGIN_ID )
    )

) );

return $form;