<?php

use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\Plugin;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
$agents = \SmartcatSupport\util\user\list_agents();
$statuses = \SmartcatSupport\util\ticket\statuses();
$products = \SmartcatSupport\util\ticket\products();

if( $plugin->edd_active || $plugin->woo_active ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'product',
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array_merge( array( 0 => __( 'All Products', \SmartcatSupport\PLUGIN_ID ) ), $products )
        )

    ) );

}

if( current_user_can( 'edit_others_tickets' ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'agent',
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array_merge( array( 0 => __( 'All Agents', \SmartcatSupport\PLUGIN_ID ) ), $agents )
        )

    ) );

}

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'status',
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array_merge( array( '' => __( 'Any Status', \SmartcatSupport\PLUGIN_ID ) ), $statuses )
        )

    ) );

return $form;
