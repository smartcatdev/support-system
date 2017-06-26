<?php

use smartcat\form\Form;
use smartcat\form\MatchConstraint;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;

$form = new Form( 'support_settings' );

$form->add_field( new TextBoxField(
    array(
        'name'      => 'first_name',
        'id'        => 'first-name',
        'class'     => array( 'form-control', 'settings-control', 'required' ),
        'label'     => __( 'First Name', 'ucare' ),
        'value'     => wp_get_current_user()->first_name,
        'error_msg' => __( 'First name is required', 'ucare' ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'      => 'last_name',
        'id'        => 'last-name',
        'class'     => array( 'form-control', 'settings-control', 'required' ),
        'label'     => __( 'Last Name', 'ucare' ),
        'value'     => wp_get_current_user()->last_name,
        'error_msg' => __( 'Last name is required', 'ucare' ),
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
        'label'     => __( 'New Password', 'ucare' )
    )

) )->add_field( new TextBoxField(
    array(
        'name'      => 'confirm_password',
        'id'        => 'confirm-password',
        'class'     => array( 'form-control', 'settings-control' ),
        'type'      => 'password',
        'error_msg' => __( 'Passwords don\'t match', 'ucare' ),
        'label'     => __( 'Confirm Password', 'ucare' ),
        'constraints'   => array(
            new MatchConstraint( isset( $_REQUEST['new_password'] ) ? $_REQUEST['new_password'] : '' )
        )
    )

) );

return $form;
