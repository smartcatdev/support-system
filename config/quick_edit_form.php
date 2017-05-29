<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;

$agents = \ucare\util\list_agents();
$statuses = \ucare\util\statuses();
$priorities = \ucare\util\priorities();

$agents = array( 0 => __( 'Unassigned', \ucare\PLUGIN_ID ) ) + $agents;

$form = new Form( 'ticket_quick_edit' );

$form->add_field( new SelectBoxField(
    array(
        'name'          => 'agent',
        'class'         => array( 'quick-edit-field', 'agent' ),
        'label'         => __( 'Assigned', \ucare\PLUGIN_ID ),
        'options'       => $agents,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'          => 'status',
        'class'         => array( 'quick-edit-field', 'status' ),
        'label'         => __( 'Status', \ucare\PLUGIN_ID ),
        'options'       => $statuses,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'          => 'priority',
        'class'         => array( 'quick-edit-field', 'priority' ),
        'label'         => __( 'Priority', \ucare\PLUGIN_ID ),
        'options'       => $priorities,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )
) );

return $form;
