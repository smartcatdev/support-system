<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_NAME;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents( array( '' => __( 'Unassigned', PLUGIN_NAME ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'support_metabox' );

$form->add_field( new SelectBoxField(
    array(
        'id'            => 'agent',
        'label'         => __( 'Assigned', PLUGIN_NAME ),
        'options'       => $agents,
        'value'         => get_post_meta( $post->ID, 'agent', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'status',
        'label'         => __( 'Status', PLUGIN_NAME ),
        'options'       => $statuses,
        'value'         => get_post_meta( $post->ID, 'status', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'label'       => __( 'Priority', PLUGIN_NAME ),
        'options'     => $priorities,
        'value'       => get_post_meta( $post->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
