<?php
/**
 * Template for the status sidebar section of a single ticket.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>

<!-- panel-body -->

    <div class="lead">

        <?php ticket_status( $ticket ); ?>

        <?php if( !empty( $category ) ) : ?>
       
            <span style="<?php echo get_term_meta( $term_id, 'category_color', true ) ? 'border-left: 8px solid ' . esc_attr( get_term_meta( $term_id, 'category_color', true ) ) : '' ?>" 
                  class="tag category">
                <?php echo $category; ?>
            </span>

        <?php endif; ?>

    </div>

    <hr class="sidebar-divider">

    <?php if ( empty( $closed_date ) ) : ?>

        <p>
            <?php time_diff( $ticket->post_modified_gmt ); ?>

            <?php if ( $stale ) : ?>

                <span class="glyphicon glyphicon-time ticket-stale"></span>

            <?php endif; ?>

        </p>

    <?php else : ?>

        <p>

            <?php if ( $closed_by > 0 ) : ?>

                <?php printf( __( 'Closed by %s', 'ucare' ), get_user_field( 'display_name', $closed_by ) ); ?>

            <?php else : ?>

                <?php _e( 'Automatically closed ', 'ucare' ); ?>

            <?php endif; ?>

            (<?php time_diff( get_gmt_from_date( $closed_date ) ); ?>)

        </p>

    <?php endif; ?>

    <p><?php printf( __( 'From %s', 'ucare' ), get_the_date( __( 'l F j, Y @ g:i A', 'ucare' ), $ticket ) ); ?></p>

<!-- /panel-body -->