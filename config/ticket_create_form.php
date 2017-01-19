<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$user     = wp_get_current_user();
$products = apply_filters( 'support_list_products', array( '' => __( 'Select a Product', Plugin::ID ) ) );
$form     = new Form( 'create_ticket' );

$form->add_field( new TextBoxField(
    array(
        'id'            => 'first_name',
        'value'         => $user->first_name,
        'label'         => __( 'First Name', Plugin::ID ),
        'error_msg'     => __( 'Cannot be blank', Plugin::ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'last_name',
        'value'         => $user->last_name,
        'label'         => __( 'Last Name', Plugin::ID ),
        'error_msg'     => __( 'Cannot be blank', Plugin::ID ),
        'constraints'   =>  array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'email',
        'type'              => 'email',
        'value'             => $user->user_email,
        'label'             => __( 'Contact Email', Plugin::ID ),
        'error_msg'         => __( 'Cannot be blank', Plugin::ID ),
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
            'label'         => __( 'Product', Plugin::ID ),
            'error_msg'     => __( 'Please Select a product', Plugin::ID ),
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
        'label'         => __( 'Subject', Plugin::ID ),
        'error_msg'     => __( 'Cannot be blank', Plugin::ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', Plugin::ID ),
        'error_msg'     => __( 'Cannot be blank', Plugin::ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextAreaField(
    array(
        'id'            => 'content',
        'label'         => __( 'Description', Plugin::ID ),
        'error_msg'     => __( 'Cannot be blank', Plugin::ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) );

return $form;
