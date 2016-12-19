<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use const SmartcatSupport\TEXT_DOMAIN;

$agents     = array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + get_agents();
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'support_metabox' );

$form->add_field( new SelectBoxField(
    array(
        'id'            => 'agent',
        'label'         => __( 'Assigned', TEXT_DOMAIN ),
        'options'       => $agents,
        'value'         => get_post_meta( $post->ID, 'agent', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'status',
        'label'         => __( 'Status', TEXT_DOMAIN ),
        'options'       => $statuses,
        'value'         => get_post_meta( $post->ID, 'status', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'label'       => __( 'Priority', TEXT_DOMAIN ),
        'options'     => $priorities,
        'value'       => get_post_meta( $post->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;