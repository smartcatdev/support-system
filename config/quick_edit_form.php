<?php

use smartcat\form\CheckBoxField;
use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents( array( '' => __( 'Unassigned', \SmartcatSupport\PLUGIN_ID ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::$STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::$PRIORITIES );

$form = new Form( 'ticket_quick_edit' );

$form->add_field( new CheckBoxField(
    array(
        'id'        => 'flagged',
        'class'     => array( 'quick-edit-field', 'flagged' ),
        'cb_title'  => __( 'Flagged', \SmartcatSupport\PLUGIN_ID ),
        'value'     => false
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'agent',
        'class'         => array( 'quick-edit-field', 'agent' ),
        'label'         => __( 'Assigned', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $agents,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'status',
        'class'         => array( 'quick-edit-field', 'status' ),
        'label'         => __( 'Status', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $statuses,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'priority',
        'class'         => array( 'quick-edit-field', 'priority' ),
        'label'         => __( 'Priority', \SmartcatSupport\PLUGIN_ID ),
        'options'       => $priorities,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )
) );

return $form;