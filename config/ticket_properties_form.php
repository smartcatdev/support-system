<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;

$agents = \ucare\util\list_agents();
$statuses = \ucare\util\statuses();
$priorities = \ucare\util\priorities();

$agents = array( 0 => __( 'Unassigned', 'ucare' ) ) + $agents;

$form = new Form( 'ticket-properties' );

$form->add_field( new SelectBoxField(
    array(
        'name'        => 'agent',
        'label'       => __( 'Assigned to', 'ucare' ),
        'class'       => array( 'form-control', 'property-control' ),
        'options'     => $agents,
        'value'       => get_post_meta( $ticket->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'status',
        'label'       => __( 'Status', 'ucare' ),
        'class'       => array( 'form-control', 'property-control' ),
        'options'     => $statuses,
        'value'       => get_post_meta( $ticket->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'priority',
        'label'       => __( 'Priority', 'ucare' ),
        'class'       => array( 'form-control', 'property-control' ),
        'options'     => $priorities,
        'value'       => get_post_meta( $ticket->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
