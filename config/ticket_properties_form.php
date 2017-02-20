<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents( array( '' => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::$STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::$PRIORITIES );

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
