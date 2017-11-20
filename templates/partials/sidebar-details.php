<?php
/**
 * Template for the status sidebar section of a single ticket.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;
$statuses = \ucare\util\statuses();
$status = get_post_meta( $ticket->ID, 'status', true );


$closed_date = get_post_meta( $ticket->ID, 'closed_date', true );
$closed_by = get_post_meta( $ticket->ID, 'closed_by', true );


?>

<div class="lead">

    <?php _e( ( array_key_exists( $status, $statuses ) ? $statuses[ $status ] : '—' ), 'ucare' ); ?>

    <?php $terms = get_the_terms( $ticket, 'ticket_category' ); ?>

    <?php if( !empty( $terms ) ) : ?>

        <span class="tag category"><?php echo $terms[0]->name; ?></span>

    <?php endif; ?>

</div>

<hr class="sidebar-divider">

<?php if( empty( $closed_date ) ) : ?>

    <p>
        <?php _e( 'Since ', 'ucare' ); ?><?php echo \ucare\util\just_now( $ticket->post_modified ); ?>

        <?php if( get_post_meta( $ticket->ID, 'stale', true ) ) : ?>

            <span class="glyphicon glyphicon-time ticket-stale"></span>

        <?php endif; ?>

    </p>

<?php else : ?>

    <p>

        <?php if( $closed_by > 0 ) : ?>

            <?php _e( 'Closed by ', 'ucare' ); ?><?php echo \ucare\util\user_full_name( get_user_by( 'id', $closed_by ) ); ?>

        <?php else : ?>

            <?php _e( 'Automatically closed ', \ucare\PLUGIN_ID ); ?>

        <?php endif; ?>

        (<?php echo \ucare\util\just_now( $closed_date ); ?>)

    </p>

<?php endif; ?>

<p><?php _e( 'From ' . get_the_date( 'l F j, Y @ g:i A', $ticket ), 'ucare' ); ?></p>
