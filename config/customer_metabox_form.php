<?php

use smartcat\form\Form;
use smartcat\form\TextBoxField;
use SmartcatSupport\Plugin;

$form = new Form( 'customer_meta_box' );

$form->add_field( new TextBoxField(
    array(
        'id'                => 'email',
        'type'              => 'email',
        'label'             => __( 'Contact Email', Plugin::ID ),
        'value'             => get_post_meta( $post->ID, 'email', true ),
        'sanitize_callback' => 'sanitize_email'
    )
) )->add_field( new TextBoxField(
    array(
        'id'    => 'website_url',
        'type'              => 'url',
        'label'             => __( 'Website', Plugin::ID ),
        'value'             => get_post_meta( $post->ID, 'website_url', true )
    )
) );

return $form;