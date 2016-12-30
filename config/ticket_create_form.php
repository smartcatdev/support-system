<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;
use function SmartcatSupport\get_products;
use const SmartcatSupport\PLUGIN_NAME;

$user     = wp_get_current_user();
$products = apply_filters( 'support_list_products', array( '' => __( 'Select a Product', PLUGIN_NAME ) ) );
$form     = new Form( 'create_ticket' );

$form->add_field( new TextBoxField(
    array(
        'id'            => 'first_name',
        'value'         => $user->first_name,
        'label'         => __( 'First Name', PLUGIN_NAME ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_NAME ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'last_name',
        'value'         => $user->last_name,
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
        'value'             => $user->user_email,
        'label'             => __( 'Contact Email', PLUGIN_NAME ),
        'error_msg'         => __( 'Cannot be blank', PLUGIN_NAME ),
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
            'label'         => __( 'Product', PLUGIN_NAME ),
            'error_msg'     => __( 'Please Select a product', PLUGIN_NAME ),
            'options'       => $products,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $products ) )
            )
        )

    ) );
}

$form->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', PLUGIN_NAME ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_NAME ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', PLUGIN_NAME ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_NAME ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextAreaField(
    array(
        'id'            => 'content',
        'label'         => __( 'Description', PLUGIN_NAME ),
        'error_msg'     => __( 'Cannot be blank', PLUGIN_NAME ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) );

return $form;