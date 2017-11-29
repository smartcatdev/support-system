<?php
/**
 * Template for customer details in single ticket.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>

<!-- panel-body -->

    <div class="media">

        <div class="media-left">

            <?php echo get_avatar( $author, 48, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

        </div>

        <div class="media-body">

            <p>
                <strong class="media-middle">
                    <?php esc_html_e( get_user_field( 'display_name', $author ) ); ?>
                </strong>
            </p>

            <p><?php printf( __( 'Email: %s', 'ucare' ), get_user_field( 'user_email', $author ) ); ?></p>

        </div>

    </div>

    <ul class="list-group customer-stats">

        <li class="list-group-item">

            <span class="lead"><?php esc_html_e( $total ); ?></span>
            <span><?php echo sprintf( __( '%s total', 'ucare' ), _n( 'Ticket', 'Tickets', $total, 'ucare' ) ); ?></span>

        </li>

        <li class="list-group-item">

            <span class="lead"><?php esc_html_e( $recent->post_count + 1 ); ?></span>
            <span><?php echo sprintf( __( '%s in the past 30 days', 'ucare' ), _n( 'Ticket', 'Tickets', $recent->post_count + 1, 'ucare' ) ); ?></span>

        </li>

        <li class="list-group-item recent-tickets">

            <p class="panel-title"><?php _e( 'Recent Tickets', 'ucare' ); ?></p>

            <?php if ( $recent->have_posts() ) : ?>

                <ul>

                    <?php foreach ( array_splice( $recent->posts, 0, 3 ) as $post ) : ?>

                        <li class="recent-ticket">
                            <strong>#<?php esc_html_e( $post->ID ); ?></strong> <?php esc_html_e( $post->post_title ); ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            <?php else : ?>

                <small class="text-muted"><?php _e( 'No tickets yet', 'ucare' ); ?></small>

            <?php endif; ?>

        </li>

    </ul>

<!-- /panel-body -->
