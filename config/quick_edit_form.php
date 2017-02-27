<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;

$agents = \SmartcatSupport\util\user\list_agents();
$statuses = \SmartcatSupport\util\ticket\statuses();
$priorities = \SmartcatSupport\util\ticket\priorities();

$agents = array( 0 => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ) + $agents;

$form = new Form( 'ticket_quick_edit' );

$form->add_field( new SelectBoxField(
    array(
        'name'          => 'agent',
        'class'         => array( 'quick-edit-field', 'agent' ),
        'label'         => __( 'Assigned', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $agents,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'          => 'status',
        'class'         => array( 'quick-edit-field', 'status' ),
        'label'         => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $statuses,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'          => 'priority',
        'class'         => array( 'quick-edit-field', 'priority' ),
        'label'         => __( 'Priority', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $priorities,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )
) );

return $form;
