<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\form\TextBoxField;
use function SmartcatSupport\get_products;
use const SmartcatSupport\TEXT_DOMAIN;

$products = array( '' => __( 'Select a Product', TEXT_DOMAIN ) ) + get_products();

$form = new Form( 'product_metabox' );

$form->add_field( new TextBoxField(
    array(
        'id'                => 'receipt_id',
        'type'              => 'text',
        'label'             => __( 'Receipt #', TEXT_DOMAIN ),
        'value'             => get_post_meta( $post->ID, 'receipt_id', true ),
        'sanitize_callback' => 'sanitize_text_field'
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'product',
        'label'       => __( 'Product', TEXT_DOMAIN ),
        'value'       => get_post_meta( $post->ID, 'product', true ),
        'options'     => $products,
        'constraints' => array(
            new ChoiceConstraint( array_keys( $products ) )
        )
    )

) );

return $form;