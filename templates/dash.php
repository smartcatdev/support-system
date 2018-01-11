<?php

namespace ucare;

?>

<div id="support-dashboard-page">

    <?php $widget = current_user_can( 'manage_support_tickets' )
        ? stripslashes( get_option( Options::AGENT_WIDGET_AREA, Defaults::AGENT_WIDGET_AREA ) )
        : stripslashes( get_option( Options::USER_WIDGET_AREA,  Defaults::USER_WIDGET_AREA  ) ); ?>

    <?php if( !empty( $widget ) ) : ?>

        <div class="container-fluid">

            <div class="row widget-wrapper">

                <div class="col-sm-12">
                    <?php echo $widget; ?>
                </div>

            </div>

        </div>

    <?php endif; ?>

    <?php if( current_user_can( 'manage_support_tickets' ) ) : ?>

        <div class="container-fluid">

            <div class="row statistics-wrapper">
                <div id="statistics-container"></div>
            </div>

        </div>

    <?php endif; ?>

    <?php if ( ucare_is_support_admin() ) the_toolbar(); // TODO support toolbar context for different user levels ?>

    <div class="container-fluid">

        <div class="row ticket-area-wrapper">

            <div class="text-right">
                <div class="clear"></div>
            </div>

            <div id="tabs">

                <ul class="nav nav-tabs ticket-nav-tabs">

                    <li class="tab active">
                        <a data-toggle="tab" href="#tickets"><?php _e( 'Tickets', 'ucare' ); ?></a>
                    </li>

                </ul>

                <div class="tab-content ticket-tab-panels">

                    <div id="tickets" class="tab-pane fade in active">
                        <?php include_once 'ticket_filter.php'; ?>
                        <div id="tickets-container" class="row"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
