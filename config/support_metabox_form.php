<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents( array( '' => __( 'Unassigned', PLUGIN_ID ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'support_metabox' );

$form->add_field( new SelectBoxField(
    array(
        'id'            => 'agent',
        'label'         => __( 'Assigned', PLUGIN_ID ),
        'options'       => $agents,
        'value'         => get_post_meta( $post->ID, 'agent', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'status',
        'label'         => __( 'Status', PLUGIN_ID ),
        'options'       => $statuses,
        'value'         => get_post_meta( $post->ID, 'status', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'label'       => __( 'Priority', PLUGIN_ID ),
        'options'     => $priorities,
        'value'       => get_post_meta( $post->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
