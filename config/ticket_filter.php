<?php

use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\Plugin;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
$agents = \SmartcatSupport\util\user\list_agents();
$statuses = \SmartcatSupport\util\ticket\statuses();
$products = \SmartcatSupport\util\ticket\products();

if( \SmartcatSupport\util\ticket\ecommerce_enabled() ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'product',
            'props'   => array(
                'data-default' => array( 0 )
            ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array_merge( array( 0 => __( 'All Products', \SmartcatSupport\PLUGIN_ID ) ), $products )
        )

    ) );

}

if( current_user_can( 'manage_support_tickets' ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'agent',
            'props'    => array(
                'data-default' => array( 0 )
            ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array_merge( array( 0 => __( 'All Agents', \SmartcatSupport\PLUGIN_ID ) ), $agents )
        )

    ) );

}

$form->add_field( new SelectBoxField(
    array(
        'name'    => 'status',
        'props'    => array(
            'data-default' => array( '' )
        ),
        'class'   => array( 'filter-field', 'form-control' ),
        'options' => array_merge( array( '' => __( 'Any Status', \SmartcatSupport\PLUGIN_ID ) ), $statuses )
    )

) );

return $form;
