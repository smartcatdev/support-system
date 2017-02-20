<?php

use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$form = new Form( 'support_settings' );

$form->add_field( new TextBoxField(
    array(
        'id'        => 'first_name',
        'class'       => array( 'form-control' ),
        'label'     => __( 'First Name', \SmartcatSupport\PLUGIN_ID ),
        'value'     => wp_get_current_user()->first_name,
        'error_msg'     => __( 'Cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'last_name',
        'class'     => array( 'form-control' ),
        'label'     => __( 'Last Name', \SmartcatSupport\PLUGIN_ID ),
        'value'     => wp_get_current_user()->last_name,
        'error_msg'     => __( 'Cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'new_password',
        'class'     => array( 'form-control' ),
        'type'      => 'password',
        'label'     => __( 'New Password', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextBoxField(
    array(
        'id'        => 'confirm_password',
        'class'     => array( 'form-control', 'confirm-password' ),
        'type'      => 'password',
        'label'     => __( 'Confirm Password', \SmartcatSupport\PLUGIN_ID )
    )

) );

return $form;