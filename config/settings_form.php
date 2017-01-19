<?php

use smartcat\form\Form;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$form = new Form( 'support_settings' );

$form->add_field( new TextBoxField(
    array(
        'id'        => 'first_name',
        'label'     => __( 'First Name', Plugin::ID ),
        'value'     => wp_get_current_user()->first_name
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'last_name',
        'label'     => __( 'Last Name', Plugin::ID ),
        'value'     => wp_get_current_user()->last_name
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'new_password',
        'type'      => 'password',
        'label'     => __( 'New Password', Plugin::ID )
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'confirm_password',
        'type'      => 'password',
        'label'     => __( 'Confirm Password', Plugin::ID )
    )

) );

return $form;