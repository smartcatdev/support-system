<?php

use smartcat\form\CheckBoxField;
use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_NAME;
use SmartcatSupport\util\UserUtils;

$agents     = UserUtils::list_agents(array( '' => __( 'Unassigned', PLUGIN_NAME ) ) );
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'ticket_quick_edit' );

$form->add_field( new CheckBoxField(
    array(
        'id'        => 'flagged',
        'cb_title'  => __( 'Flagged', PLUGIN_NAME ),
        'value'     => false
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'agent',
        'label'         => __( 'Assigned', PLUGIN_NAME ),
        'options'       => $agents,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'status',
        'label'         => __( 'Status', PLUGIN_NAME ),
        'options'       => $statuses,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'priority',
        'label'         => __( 'Priority', PLUGIN_NAME ),
        'options'       => $priorities,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )
) );

return $form;