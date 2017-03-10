<?php

use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;
use smartcat\form\UniqueEmailConstraint;

$form = new Form( 'register_form' );

$form->add_field( new TextBoxField(
    array(
        'name'          => 'first_name',
        'class'         => array( 'form-control' ),
        'label'         => __( 'First Name', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'First name cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'          => 'last_name',
        'class'         => array( 'form-control' ),
        'label'         => __( 'Last Name', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Last name cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   =>  array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'              => 'email',
        'class'             => array( 'form-control' ),
        'type'              => 'email',
        'label'             => __( 'Email Address', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'         => __( 'Email cannot be empty or already in use', \SmartcatSupport\PLUGIN_ID ),
        'sanitize_callback' => 'sanitize_email',
        'constraints'       => array(
            new RequiredConstraint(), new UniqueEmailConstraint()
        )
    )
) );

return $form;
