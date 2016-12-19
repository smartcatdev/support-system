<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\HiddenField;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use const SmartcatSupport\TEXT_DOMAIN;

$agents     = get_agents();
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'meta_form' );

$form->add_field( new HiddenField(
    array(
        'id'    => 'id',
        'value' => $post->ID
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'agent',
        'error_msg'   => __( 'Invalid agent selected', TEXT_DOMAIN ),
        'label'       => __( 'Assigned To', TEXT_DOMAIN ),
        'options'     => array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + $agents,
        'value'       => get_post_meta( $post->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'status',
        'error_msg'   => __( 'Invalid status selected', TEXT_DOMAIN ),
        'label'       => __( 'Status', TEXT_DOMAIN ),
        'options'     => $statuses,
        'value'       => get_post_meta( $post->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'error_msg'   => __( 'Invalid priority selected', TEXT_DOMAIN ),
        'label'       => __( 'Priority', TEXT_DOMAIN ),
        'options'     => $priorities,
        'value'       => get_post_meta( $post->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );
