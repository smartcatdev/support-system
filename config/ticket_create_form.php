<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$products = apply_filters( 'support_list_products', array( '' => __( 'Select a Product', Plugin::ID ) ) );
$form     = new Form( 'create_ticket' );

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

    ) )->add_field( new TextBoxField(
        array(
            'id'                => 'receipt_id',
            'label'             => __( 'Receipt #', Plugin::ID ),
            'sanitize_callback' => 'sanitize_text_field',
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
