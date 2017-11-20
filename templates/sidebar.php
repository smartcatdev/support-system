<?php

namespace ucare;

?>

<div class="panel-group">

    <div class="panel panel-default ticket-details" data-id="ticket-details">

        <div class="panel-body">

            <?php get_details_sidebar( $ticket ); ?>

        </div>

    </div>

    <?php foreach ( get_sidebar_sections( $ticket ) as $id => $title ) : ?>

        <div class="panel panel-default <?php esc_attr_e( $id ); ?>" data-id="<?php esc_attr_e( $id ); ?>">

            <div class="panel-heading">

                <a class="panel-title" data-toggle="collapse" href="#collapse-<?php esc_attr_e( $id ); ?>-<?php echo $ticket->ID; ?>">

                    <?php esc_html_e( $title ); ?>

                </a>

            </div>

            <div id="collapse-<?php esc_attr_e( $id ); ?>-<?php echo $ticket->ID; ?>" class="panel-collapse in">

                <div class="panel-body">

                    <?php get_sidebar( $id, $ticket ); ?>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>
