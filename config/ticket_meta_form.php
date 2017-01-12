<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\HiddenField;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents( array( '' => __( 'Unassigned', PLUGIN_ID ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'meta_form' );

$form->add_field( new HiddenField(
    array(
        'id'    => 'id',
        'value' => $ticket->ID
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'agent',
        'error_msg'   => __( 'Invalid agent selected', PLUGIN_ID ),
        'label'       => __( 'Assigned To', PLUGIN_ID ),
        'options'     => $agents,
        'value'       => get_post_meta( $ticket->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'status',
        'error_msg'   => __( 'Invalid status selected', PLUGIN_ID ),
        'label'       => __( 'Status', PLUGIN_ID ),
        'options'     => $statuses,
        'value'       => get_post_meta( $ticket->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'error_msg'   => __( 'Invalid priority selected', PLUGIN_ID ),
        'label'       => __( 'Priority', PLUGIN_ID ),
        'options'     => $priorities,
        'value'       => get_post_meta( $ticket->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
