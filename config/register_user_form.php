<?php

use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\TextBoxField;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\util\UniqueEmailConstraint;

$form = new Form( 'register_form' );

$form->add_field( new TextBoxField(
    array(
        'id'            => 'first_name',
        'label'         => __( 'First Name', PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'last_name',
        'label'         => __( 'Last Name', PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_ID ),
        'constraints'   =>  array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'                => 'email',
        'type'              => 'email',
        'label'             => __( 'Email Address', PLUGIN_ID ),
        'error_msg'         => __( 'Email cannot be empty or already in use', PLUGIN_ID ),
        'sanitize_callback' => 'sanitize_email',
        'constraints'       => array(
            new RequiredConstraint(), new UniqueEmailConstraint()
        )
    )
) );

return $form;
