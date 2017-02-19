<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$products = apply_filters( 'support_list_products', array( '' => __( 'Select a Product', \SmartcatSupport\PLUGIN_ID ) ) );
$form     = new Form( 'create_ticket' );

if( $products ) {
    $form->add_field( new SelectBoxField(
        array(
            'id'            => 'product',
            'label'         => __( 'Product', \SmartcatSupport\PLUGIN_ID ),
            'error_msg'     => __( 'Please Select a product', \SmartcatSupport\PLUGIN_ID ),
            'options'       => $products,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $products ) )
            )
        )

    ) )->add_field( new TextBoxField(
        array(
            'id'                => 'receipt_id',
            'label'             => __( 'Receipt #', \SmartcatSupport\PLUGIN_ID ),
            'sanitize_callback' => 'sanitize_text_field',
        )

    ) );
}

$form->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'id'            => 'subject',
        'label'         => __( 'Subject', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextAreaField(
    array(
        'id'            => 'content',
        'label'         => __( 'Description', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) );

return $form;
