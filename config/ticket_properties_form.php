<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;

$agents = \SmartcatSupport\util\user\list_agents();
$statuses = \SmartcatSupport\util\ticket\statuses();
$priorities = \SmartcatSupport\util\ticket\priorities();

$agents = array( 0 => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ) + $agents;

$form = new Form( 'ticket-properties' );

$form->add_field( new SelectBoxField(
    array(
        'name'        => 'agent',
        'label'       => __( 'Assigned to', \SmartcatSupport\PLUGIN_ID ),
        'class'       => array( 'form-control' ),
        'options'     => $agents,
        'value'       => get_post_meta( $ticket->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'status',
        'label'       => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'class'       => array( 'form-control' ),
        'options'     => $statuses,
        'value'       => get_post_meta( $ticket->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'priority',
        'label'       => __( 'Priority', \SmartcatSupport\PLUGIN_ID ),
        'class'       => array( 'form-control' ),
        'options'     => $priorities,
        'value'       => get_post_meta( $ticket->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
