<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\RequiredConstraint;
use smartcat\form\SelectBoxField;
use smartcat\form\TextAreaField;
use smartcat\form\TextBoxField;

$products = \ucare\util\products();

$products = array( 0 => __( 'Select a Product', \ucare\PLUGIN_ID ) ) + $products;

$form = new Form( 'create_ticket' );

if( \ucare\util\ecommerce_enabled() ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'          => 'product',
            'class'         => array( 'form-control' ),
            'label'         => __( 'Product', \ucare\PLUGIN_ID ),
            'error_msg'     => __( 'Please Select a product', \ucare\PLUGIN_ID ),
            'options'       => $products,
            'props'         => array(
                'data-default' => array( 0 )
            ),
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $products ) )
            )
        )

    ) )->add_field( new TextBoxField(
        array(
            'name'              => 'receipt_id',
            'class'             => array( 'form-control' ),
            'label'             => __( 'Receipt #', \ucare\PLUGIN_ID ),
            'sanitize_callback' => 'sanitize_text_field',
            'props'             => array(
                'data-default' => array( '' )
            ),
        )

    ) );
}

$form->add_field( new TextBoxField(
    array(
        'name'          => 'subject',
        'class'         => array( 'form-control' ),
        'label'         => __( 'Subject', \ucare\PLUGIN_ID ),
        'error_msg'     => __( 'Cannot be blank', \ucare\PLUGIN_ID ),
        'sanitize_callback' => 'sanitize_text_field',
        'props'         => array(
            'data-default' => array( '' )
        ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextBoxField(
    array(
        'name'          => 'subject',
        'class'         => array( 'form-control' ),
        'label'         => __( 'Subject', \ucare\PLUGIN_ID ),
        'error_msg'     => __( 'Subject cannot be blank', \ucare\PLUGIN_ID ),
        'sanitize_callback' => 'sanitize_text_field',
        'props'         => array(
            'data-default' => array( '' )
        ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) )->add_field( new TextAreaField(
    array(
        'name'          => 'description',
        'props'         => array( 'rows' => array( 8 ), 'data-default' => array( '' ) ),
        'class'         => array( 'form-control' ),
        'label'         => __( 'Description', \ucare\PLUGIN_ID ),
        'error_msg'     => __( 'Description cannot be blank', \ucare\PLUGIN_ID ),
        'constraints'   => array(
            new RequiredConstraint()
        )
    )

) );

return $form;
