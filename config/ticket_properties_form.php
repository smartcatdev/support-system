<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\HiddenField;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents( array( '' => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::$STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::$PRIORITIES );

$form = new Form( 'meta_form' );

$form->add_field( new SelectBoxField(
    array(
        'id'          => 'agent',
        'class'       => array( 'form-control' ),
        'error_msg'   => __( 'Invalid agent selected', \SmartcatSupport\PLUGIN_ID ),
        'label'       => __( 'Assigned To', \SmartcatSupport\PLUGIN_ID ),
        'options'     => $agents,
        'value'       => get_post_meta( $ticket->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'status',
        'class'       => array( 'form-control' ),
        'error_msg'   => __( 'Invalid status selected', \SmartcatSupport\PLUGIN_ID ),
        'label'       => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'options'     => $statuses,
        'value'       => get_post_meta( $ticket->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'class'       => array( 'form-control' ),
        'error_msg'   => __( 'Invalid priority selected', \SmartcatSupport\PLUGIN_ID ),
        'label'       => __( 'Priority', \SmartcatSupport\PLUGIN_ID ),
        'options'     => $priorities,
        'value'       => get_post_meta( $ticket->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
