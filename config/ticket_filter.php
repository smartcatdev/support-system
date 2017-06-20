<?php

use smartcat\form\CheckBoxField;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use smartcat\form\TextBoxField;
use ucare\descriptor\Option;
use ucare\form\CheckBoxGroup;
use ucare\Plugin;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \ucare\PLUGIN_ID );
$agents = \ucare\util\list_agents();
$products = \ucare\util\products();

if( get_option( Option::CATEGORIES_ENABLED, Option\Defaults::CATEGORIES_ENABLED ) == 'on' ) {

    $categories = array();
    $name = get_option( Option::CATEGORIES_NAME, Option\Defaults::CATEGORIES_NAME );
    $plural = get_option( Option::CATEGORIES_NAME_PLURAL, Option\Defaults::CATEGORIES_NAME_PLURAL );

    foreach( get_terms( array( 'taxonomy' => 'ticket_category', 'hide_empty' => false ) ) as $term ) {
        $categories[ $term->slug ] = $term->name;
    }

    $form->add_field( new SelectBoxField(
        array(
            'name'          => 'category',
            'class'         => array( 'filter-field', 'form-control' ),
            'label'         => __( ucwords( $name ), 'ucare' ),
            'options'       => array( 0 => __( "All $plural", 'ucare' ) ) + $categories
        )

    ) );

}

if( \ucare\util\ecommerce_enabled() ) {

    $form->add_field( new SelectBoxField(
        array(
            'id'      => 'product',
            'name'    => 'meta[product]',
            'label'   => __( 'Product', 'ucare' ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array( 0 => __( 'All Products', 'ucare' ) ) + $products
        )

    ) );

}

if( current_user_can( 'manage_support_tickets' ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'id'      => 'agent',
            'name'    => 'agent',
            'label'   => __( 'Agent', 'ucare' ),
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array(
                 0 => __( 'All Agents', 'ucare' ),
                -1 => __( 'Unassigned', 'ucare' ) ) + $agents
        )

    ) );

    $form->add_field(new TextBoxField(
        array(
            'id'    => 'email',
            'name'  => 'email',
            'label' => __( 'Email', 'ucare' ),
            'type'  => 'email',
            'class' => array('filter-field', 'form-control')
        )

    ));
}

$form->add_field( new CheckBoxField(
    array(
        'id'             => 'stale',
        'name'           => 'stale',
        'checkbox_label' => __( 'Stale', 'ucare' ),
        'value'          => '',
        'class'          => array( 'filter-field' )
    )

) )->add_field( new CheckBoxGroup(
    array(
        'id'      => 'status',
        'name'    => 'meta[status]',
        'label'   => __( 'Status', 'ucare' ),
        'value'   => \ucare\util\filter_defaults()['status'],
        'class'   => array( 'filter-field' ),
        'options' => \ucare\util\statuses()
    )

) );

return $form;
