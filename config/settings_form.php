<?php

use smartcat\form\Form;
use smartcat\form\MatchConstraint;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$form = new Form( 'support_settings' );

$form->add_field( new TextBoxField(
    array(
        'name'      => 'first_name',
        'id'        => 'first-name',
        'class'     => array( 'form-control', 'settings-control', 'required' ),
        'label'     => __( 'First Name', \SmartcatSupport\PLUGIN_ID ),
        'value'     => wp_get_current_user()->first_name,
        'error_msg' => __( 'First name is required', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'      => 'last_name',
        'id'        => 'last-name',
        'class'     => array( 'form-control', 'settings-control', 'required' ),
        'label'     => __( 'Last Name', \SmartcatSupport\PLUGIN_ID ),
        'value'     => wp_get_current_user()->last_name,
        'error_msg' => __( 'Last name is required', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'      => 'new_password',
        'id'        => 'new-password',
        'class'     => array( 'form-control', 'settings-control' ),
        'type'      => 'password',
        'label'     => __( 'New Password', \SmartcatSupport\PLUGIN_ID )
    )

) )->add_field( new TextBoxField(
    array(
        'name'      => 'confirm_password',
        'id'        => 'confirm-password',
        'class'     => array( 'form-control', 'settings-control' ),
        'type'      => 'password',
        'error_msg'     => __( 'Passwords don\'t match', \SmartcatSupport\PLUGIN_ID ),
        'label'     => __( 'Confirm Password', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new MatchConstraint( $_REQUEST[ 'new_password'] )
        )
    )

) );

return $form;
