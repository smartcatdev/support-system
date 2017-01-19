<?php

use smartcat\form\Form;
use SmartcatSupport\Plugin;
use SmartcatSupport\Plugin;

?>

<div class="support_ticket row">

    <div class="ticket support_card col-sm-8" data-id="<?php esc_attr_e( $ticket->ID ); ?>">

        <div class="status_bar">

            <div class="image_wrapper">
                
                <?php echo get_avatar( $ticket->post_author, 48 ); ?>

            </div>

            <div class="meta_wrapper">

                <p class="author_name"><?php esc_html_e( get_the_author_meta( 'display_name', $ticket->post_author ) ); ?></p>

                <p class="date_posted">

                    <?php _e( 'Updated ', Plugin::ID ); ?>
                    <?php _e( human_time_diff( strtotime( $ticket->post_date ), current_time( 'timestamp' ) ) . ' ago', Plugin::ID ); ?>

                    <?php if ( current_user_can( 'edit_others_tickets' ) ) : ?>

                        by

                        <span class="author_name">

                        <?php esc_html_e( get_the_author_meta( 'display_name', get_post_meta( $ticket->ID, '_edit_last', true ) ) ); ?>

                    </span>

                    <?php endif; ?>

                </p>

            </div>

        </div>

        <div class="inner">

            <h2 class="subject"><?php esc_html_e( $ticket->post_title ); ?></h2>

            <div class="content"><?php echo $ticket->post_content; ?></div>

        </div>

        <div class="date_opened">

            <?php _e( 'Opened ', Plugin::ID ); echo get_the_date( 'l F n, Y', $ticket ); ?>

        </div>


    </div>
    

    <?php if( current_user_can( 'edit_others_tickets' ) ) : ?>

        <div id="ticket-sidebar" class="col-sm-4">

            <form class="meta_form" data-action="support_update_ticket" data-after="refresh_tickets">

                <?php Form::render_fields( include Plugin::plugin_dir( Plugin::ID ) . 'config/ticket_meta_form.php' ); ?>

            </form>

        </div>

    <?php endif; ?>
    
    </div>
