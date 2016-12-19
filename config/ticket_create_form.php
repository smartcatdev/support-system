<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;
use function SmartcatSupport\get_products;
use const SmartcatSupport\TEXT_DOMAIN;

$user     = wp_get_current_user();
$products = get_products();
$form     = new Form( 'create_ticket' );

$form->add_field( new TextBoxField(
    array(
        'id'            => 'first_name',
        'value'         => $user->first_name,
        'label'         => __( 'First Name', TEXT_DOMAIN ),
        'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'last_name',
        'value'         => $user->last_name,
        'label'         => __( 'Last Name', TEXT_DOMAIN ),
        'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'   =>  array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'email',
        'type'              => 'email',
        'value'             => $user->user_email,
        'label'             => __( 'Contact Email', TEXT_DOMAIN ),
        'error_msg'         => __( 'Cannot be blank', TEXT_DOMAIN ),
        'sanitize_callback' => 'sanitize_email',
        'constraints'       => array(
            new RequiredConstraint()
        )
    )

) );

if( $products ) {
    $form->add_field( new SelectBoxField(
        array(
            'id'            => 'product',
            'label'         => __( 'Product', TEXT_DOMAIN ),
            'error_msg'     => __( 'Please Select a product', TEXT_DOMAIN ),
            'options'       => array( '' => __( 'Select a Product', TEXT_DOMAIN ) ) + $products,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $products ) )
            )
        )

    ) );
}

$form->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', TEXT_DOMAIN ),
        'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', TEXT_DOMAIN ),
        'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextAreaField(
    array(
        'id'            => 'content',
        'label'         => __( 'Description', TEXT_DOMAIN ),
        'error_msg'     => __( 'Cannot be blank', TEXT_DOMAIN ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) );
