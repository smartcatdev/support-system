<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\form\TextBoxField;
use const SmartcatSupport\PLUGIN_NAME;

$products = apply_filters( 'support_list_products', array( '' => __( 'Select a Product', PLUGIN_NAME ) ) );

$form = new Form( 'product_metabox' );

$form->add_field( new TextBoxField(
    array(
        'id'                => 'receipt_id',
        'type'              => 'text',
        'label'             => __( 'Receipt #', PLUGIN_NAME ),
        'value'             => get_post_meta( $post->ID, 'receipt_id', true ),
        'sanitize_callback' => 'sanitize_text_field'
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'product',
        'label'       => __( 'Product', PLUGIN_NAME ),
        'value'       => get_post_meta( $post->ID, 'product', true ),
        'options'     => $products,
        'constraints' => array(
            new ChoiceConstraint( array_keys( $products ) )
        )
    )

) );

return $form;