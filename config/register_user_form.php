<?php

use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;
use const SmartcatSupport\PLUGIN_NAME;

$form = new Form( 'register_form' );

$form->add_field( new TextBoxField(
    array(
        'id'            => 'first_name',
        'label'         => __( 'First Name', PLUGIN_NAME ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_NAME ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'last_name',
        'label'         => __( 'Last Name', PLUGIN_NAME ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_NAME ),
        'constraints'   =>  array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'email',
        'type'              => 'email',
        'label'             => __( 'Email Address', PLUGIN_NAME ),
        'error_msg'         => __( 'Cannot be blank', PLUGIN_NAME ),
        'sanitize_callback' => 'sanitize_email',
        'constraints'       => array(
            new RequiredConstraint()
        )
    )
) );

return $form;
