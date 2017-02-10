<?php

use smartcat\form\Form;
use smartcat\form\TextBoxField;
use SmartcatSupport\form\StaticField;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

$form = new Form( 'customer_meta_box' );

$form->add_field( new StaticField(
    array(
        'id'                => 'email',
        'label'             => __( 'Contact Email', Plugin::ID ),
        'value'             => TicketUtils::ticket_author_email( $post )
    )
) )->add_field( new TextBoxField(
    array(
        'id'                => 'website_url',
        'type'              => 'url',
        'label'             => __( 'Website', Plugin::ID ),
        'value'             => get_post_meta( $post->ID, 'website_url', true )
    )
) );

return $form;