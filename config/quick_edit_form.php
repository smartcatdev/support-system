<?php

use smartcat\form\CheckBoxField;
use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use SmartcatSupport\descriptor\Option;
use function SmartcatSupport\get_agents;
use const SmartcatSupport\TEXT_DOMAIN;

$agents     = array( '' => __( 'Unassigned', TEXT_DOMAIN ) ) + get_agents();
$statuses   = get_option( Option::STATUSES, Option\Defaults::STATUSES );
$priorities = get_option( Option::PRIORITIES, Option\Defaults::PRIORITIES );

$form = new Form( 'ticket_quick_edit' );

$form->add_field( new CheckBoxField(
    array(
        'id'        => 'flagged',
        'cb_title'  => __( 'Flagged', TEXT_DOMAIN ),
        'value'     => false
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'agent',
        'label'         => __( 'Assigned', TEXT_DOMAIN ),
        'options'       => $agents,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'status',
        'label'         => __( 'Status', TEXT_DOMAIN ),
        'options'       => $statuses,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'id'            => 'priority',
        'label'         => __( 'Priority', TEXT_DOMAIN ),
        'options'       => $priorities,
        'constraints'   => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )
) );

return $form;