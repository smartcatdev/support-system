<?php

use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\UserUtils;

$form = new Form( 'ticket_filter' );
$plugin = Plugin::get_plugin( \SmartcatSupport\PLUGIN_ID );

if( $plugin->edd_active || $plugin->woo_active ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'product',
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => apply_filters( 'support_list_products', array( '' => __( 'All Products', \SmartcatSupport\PLUGIN_ID ) ) )
        )

    ) );

}

if( current_user_can( 'edit_others_tickets' ) ) {

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'agent',
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => UserUtils::list_agents( array( '' => __( 'All Agents', \SmartcatSupport\PLUGIN_ID ) ) )
        )

    ) );

}

    $form->add_field( new SelectBoxField(
        array(
            'name'    => 'status',
            'class'   => array( 'filter-field', 'form-control' ),
            'options' => array( '' => __( 'Any Status', \SmartcatSupport\PLUGIN_ID ) ) + get_option( Option::STATUSES, Option\Defaults::$STATUSES )
        )

    ) );

return $form;
