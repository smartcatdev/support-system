<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;

$products = \SmartcatSupport\util\ticket\products();

$products = array_merge( array( 0 => __( 'Select a Product', \SmartcatSupport\PLUGIN_ID ) ), $products );

$form = new Form( 'create_ticket' );

if( !empty( $products ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'          => 'product',
            'class'         => array( 'form-control' ),
            'label'         => __( 'Product', \SmartcatSupport\PLUGIN_ID ),
            'error_msg'     => __( 'Please Select a product', \SmartcatSupport\PLUGIN_ID ),
            'options'       => $products,
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $products ) )
            )
        )

    ) )->add_field( new TextBoxField(
        array(
            'name'              => 'receipt_id',
            'class'             => array( 'form-control' ),
            'label'             => __( 'Receipt #', \SmartcatSupport\PLUGIN_ID ),
            'sanitize_callback' => 'sanitize_text_field',
        )

    ) );
}

$form->add_field( new TextBoxField(
    array(
        'name'          => 'subject',
        'class'         => array( 'form-control' ),
        'label'         => __( 'Subject', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'          => 'subject',
        'class'         => array( 'form-control' ),
        'label'         => __( 'Subject', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Subject cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextAreaField(
    array(
        'name'          => 'description',
        'props'         => array( 'rows' => array( 8 ) ),
        'class'         => array( 'form-control' ),
        'label'         => __( 'Description', \SmartcatSupport\PLUGIN_ID ),
        'error_msg'     => __( 'Description cannot be blank', \SmartcatSupport\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) );

return $form;
