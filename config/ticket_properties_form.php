<?php

use smartcat\form\ChoiceConstraint;
use smartcat\form\Form;
use smartcat\form\SelectBoxField;
use ucare\Options;

$agents = \ucare\util\list_agents();
$statuses = \ucare\util\statuses();
$priorities = \ucare\util\priorities();

$agents = array( 0 => __( 'Unassigned', 'ucare' ) ) + $agents;

$form = new Form( 'ticket-properties' );


if( get_option( Options::CATEGORIES_ENABLED, \ucare\Defaults::CATEGORIES_ENABLED ) == 'on' ) {

    $terms = get_the_terms( $ticket, 'ticket_category' );
    $categories = array( 0 => __( 'Select a category', 'ucare' ));
    
    foreach( get_terms( array( 'taxonomy' => 'ticket_category', 'hide_empty' => false ) ) as $key=>$term ) {
        
        $categories[ $key + 1 ] = $term->name;
    }
    $category = array_search($terms[0]->name, $categories);
    
    $form->add_field( new SelectBoxField(
        array(
            'name'          => 'category',
            'class'         => array( 'filter-field', 'form-control' ),
            'label'         => __( 'Category', 'ucare' ),
            'options'       => $categories,
            'value'         => ($category ? $category : get_post_meta( $ticket->ID, 'category', true )),
            'constraints'   => array(
                new ChoiceConstraint( array_keys( $categories ) )
            )
        )
    ) );
}

$form->add_field( new SelectBoxField(
    array(
        'name'        => 'agent',
        'label'       => __( 'Assigned to', 'ucare' ),
        'class'       => array( 'form-control', 'property-control' ),
        'options'     => $agents,
        'value'       => get_post_meta( $ticket->ID, 'agent', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $agents ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'status',
        'label'       => __( 'Status', 'ucare' ),
        'class'       => array( 'form-control', 'property-control' ),
        'options'     => $statuses,
        'value'       => get_post_meta( $ticket->ID, 'status', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $statuses ) )
        )
    )

) )->add_field( new SelectBoxField(
    array(
        'name'        => 'priority',
        'label'       => __( 'Priority', 'ucare' ),
        'class'       => array( 'form-control', 'property-control' ),
        'options'     => $priorities,
        'value'       => get_post_meta( $ticket->ID, 'priority', true ),
        'constraints' => array(
            new ChoiceConstraint( array_keys( $priorities ) )
        )
    )

) );

return $form;
