<?php

namespace ucare;

?>

<div class="panel-group">

    <div class="panel panel-default ticket-details" data-id="ticket-details">

        <div class="panel-body">

            <?php get_details_sidebar( $ticket ); ?>

        </div>

    </div>

    <?php foreach ( get_sidebars() as $id => $section ) : ?>

        <div class="panel panel-default <?php esc_attr_e( $id ); ?>" data-id="<?php esc_attr_e( $id ); ?>">

            <div class="panel-heading">

                <a data-toggle="collapse"
                   class="panel-title <?php echo $section['collapse'] ? 'collapsed' : ''; ?>"
                   href="#collapse-<?php esc_attr_e( $id ); ?>-<?php echo $ticket->ID; ?>">

                    <?php esc_html_e( $section['title'] ); ?>

                </a>

            </div>

            <div id="collapse-<?php esc_attr_e( $id ); ?>-<?php echo $ticket->ID; ?>"
                 class="panel-collapse collapse <?php echo $section['collapse'] ? 'collapsed' : ''; ?>">

                <div class="panel-body">

                    <?php get_sidebar( $id, $ticket ); ?>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>
