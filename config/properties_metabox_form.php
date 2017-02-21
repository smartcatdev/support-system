<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;

$agents = \SmartcatSupport\util\user\list_agents();
$statuses = \SmartcatSupport\util\ticket\statuses();
$priorities = \SmartcatSupport\util\ticket\priorities();

$agents = array_merge( array( 0 => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ), $agents );

$form = new Form( 'support_metabox' );

$form->add_field( new SelectBoxField(
    array(
        'name'          => 'agent',
        'class'         => array( 'metabox-field' ),
        'label'         => __( 'Assigned', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $agents,
        'value'         => get_post_meta( $post->ID, 'agent', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'          => 'status',
        'class'         => array( 'metabox-field' ),
        'label'         => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $statuses,
        'value'         => get_post_meta( $post->ID, 'status', true ),
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'priority',
        'class'       => array( 'metabox-field' ),
        'label'       => __( 'Priority', \SmartcatSupport\PLUGIN_ID ),
        'options'     => $priorities,
        'value'       => get_post_meta( $post->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
