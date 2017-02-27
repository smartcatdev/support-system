<?php

use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\form\SearchBox;
use SmartcatSupport\Plugin;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
$agents = \SmartcatSupport\util\user\list_agents();
$statuses = \SmartcatSupport\util\ticket\statuses();
$products = \SmartcatSupport\util\ticket\products();

if( \SmartcatSupport\util\ticket\ecommerce_enabled() ) {

    $form->add_field( new SelectBoxField(
        array(
            'id'      => 'product',
            'name'    => 'product',
            'label'   => __( 'Product', \SmartcatSupport\PLUGIN_ID ),
            'props'   => array(
                'data-default' => array( 0 )
            ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array( 0 => __( 'All Products', \SmartcatSupport\PLUGIN_ID ) ) + $products
        )

    ) );

}

if( current_user_can( 'manage_support_tickets' ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'id'      => 'agent',
            'name'    => 'agent',
            'label'   => __( 'Agent', \SmartcatSupport\PLUGIN_ID ),
            'props'   => array(
                'data-default' => array( 0 )
            ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array( 0 => __( 'All Agents', \SmartcatSupport\PLUGIN_ID ) ) + $agents
        )

    ) );

}

$form->add_field( new SelectBoxField(
    array(
        'id'      => 'status',
        'name'    => 'status',
        'label'   => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'props'    => array(
            'data-default' => array( '' )
        ),
        'class'   => array( 'filter-field', 'form-control' ),
        'options' => array( '' => __( 'Any Status', \SmartcatSupport\PLUGIN_ID ) ) + $statuses
    )

) );

//$form->add_field( new SearchBox(
//    array(
//        'id'      => 'search',
//        'name'    => 'search',
//        'props'   => array(
//            'data-default' => array( '' ),
//            'placeholder' => array( 'Search' )
//        ),
//        'class'   => array( 'filter-field', 'form-control' )
//    )
//
//) );

return $form;
