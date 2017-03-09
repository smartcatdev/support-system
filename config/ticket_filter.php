<?php

use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\form\TextBoxField;
use SmartcatSupport\form\CheckBoxGroup;
use SmartcatSupport\Plugin;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );
$agents = \SmartcatSupport\util\list_agents();
$products = \SmartcatSupport\util\products();

if( \SmartcatSupport\util\ecommerce_enabled() ) {

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

if( current_user_can( 'manage_support_tickets' ) ) {

    $form->add_field(new TextBoxField(
        array(
            'id'    => 'email',
            'name'  => 'email',
            'label' => __( 'Email', \SmartcatSupport\PLUGIN_ID ),
            'type'  => 'email',
            'class' => array('filter-field', 'form-control')
        )

    ));
}

$form->add_field( new CheckBoxGroup(
    array(
        'id'      => 'status',
        'name'    => 'status',
        'label'   => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'props'    => array(
            'checked'       => array( 'checked' ),
            'data-default'  => array( 'true' )
        ),
        'class'   => array( 'filter-field' ),
        'options' => \SmartcatSupport\util\statuses()
    )

) );

return $form;
