<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\HiddenAbstractField;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use const SmartcatSupport\PLUGIN_NAME;

$agents     = array( '' => __( 'Unassigned', PLUGIN_NAME ) ) + get_agents();
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'meta_form' );

$form->add_field( new HiddenAbstractField(
    array(
        'id'    => 'id',
        'value' => $post->ID
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'agent',
        'error_msg'   => __( 'Invalid agent selected', PLUGIN_NAME ),
        'label'       => __( 'Assigned To', PLUGIN_NAME ),
        'options'     => $agents,
        'value'       => get_post_meta( $post->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'status',
        'error_msg'   => __( 'Invalid status selected', PLUGIN_NAME ),
        'label'       => __( 'Status', PLUGIN_NAME ),
        'options'     => $statuses,
        'value'       => get_post_meta( $post->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'          => 'priority',
        'error_msg'   => __( 'Invalid priority selected', PLUGIN_NAME ),
        'label'       => __( 'Priority', PLUGIN_NAME ),
        'options'     => $priorities,
        'value'       => get_post_meta( $post->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
