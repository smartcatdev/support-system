<?php

use smartcat\form\CheckBoxField;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\form\TextBoxField;
use ucare\form\CheckBoxGroup;
use ucare\Plugin;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
$agents = \ucare\util\list_agents();
$products = \ucare\util\products();

if( \ucare\util\ecommerce_enabled() ) {

    $form->add_field( new SelectBoxField(
        array(
            'id'      => 'product',
            'name'    => 'meta[product]',
            'label'   => __( 'Product', \ucare\PLUGIN_ID ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array( 0 => __( 'All Products', \ucare\PLUGIN_ID ) ) + $products
        )

    ) );

}

if( current_user_can( 'manage_support_tickets' ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'id'      => 'agent',
            'name'    => 'agent',
            'label'   => __( 'Agent', \ucare\PLUGIN_ID ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array(
                 0 => __( 'All Agents', \ucare\PLUGIN_ID ),
                -1 => __( 'Unassigned', \ucare\PLUGIN_ID ) ) + $agents
        )

    ) );

    $form->add_field(new TextBoxField(
        array(
            'id'    => 'email',
            'name'  => 'email',
            'label' => __( 'Email', \ucare\PLUGIN_ID ),
            'type'  => 'email',
            'class' => array('filter-field', 'form-control')
        )

    ));
}

$form->add_field( new CheckBoxField(
    array(
        'id'             => 'stale',
        'name'           => 'stale',
        'checkbox_label' => __( 'Stale', \ucare\PLUGIN_ID ),
        'value'          => '',
        'class'          => array( 'filter-field' )
    )

) )->add_field( new CheckBoxGroup(
    array(
        'id'      => 'status',
        'name'    => 'meta[status]',
        'label'   => __( 'Status', \ucare\PLUGIN_ID ),
        'value'   => \ucare\util\filter_defaults()['status'],
        'class'   => array( 'filter-field' ),
        'options' => \ucare\util\statuses()
    )

) );

return $form;
