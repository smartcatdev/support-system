<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket_detail">

    <div class="status_bar">

        <div class="image_wrapper">

            <?php echo get_avatar( $post, 48 ); ?>

        </div>

        <div class="meta_wrapper">

            <p class="author_name"><?php esc_html_e( get_the_author_meta( 'display_name', $post->post_author ) ); ?></p>

            <p class="date_posted">

                <?php _e( 'Updated ', TEXT_DOMAIN ); ?>
                <?php _e( human_time_diff( strtotime( $post->post_date ), current_time( 'timestamp' ) ) . ' ago', TEXT_DOMAIN ); ?>

                <?php if ( current_user_can( 'edit_others_tickets' ) ) : ?>

                    by

                    <span class="author_name">

                            <?php esc_html_e( get_the_author_meta( 'display_name', get_post_meta( $post->ID, '_edit_last', true ) ) ); ?>

                        </span>

                <?php endif; ?>

            </p>

        </div>

    </div>

    <div class="ticket_editor">

        <form class="edit_ticket_form" data-action="<?php esc_attr_e( $ticket_action ); ?>">

            <?php Form::form_fields( $editor_form ); ?>

            <div class="meta_fields">

                <?php Form::form_fields( $meta_form ); ?>

            </div>

            <div class="submit_button_wrapper hidden">

                <button class="submit_button">

                    <div class="status hidden"></div>

                    <span class="text"
                          data-default="<?php _e( 'Save', TEXT_DOMAIN ); ?>"
                          data-success="<?php _e( 'Saved', TEXT_DOMAIN ); ?>"
                          data-fail="<?php _e( 'Error', TEXT_DOMAIN ); ?>"
                          data-wait="<?php _e( 'Saving', TEXT_DOMAIN ); ?>">

                          <?php _e( 'Save', TEXT_DOMAIN ); ?>

                    </span>

                </button>

            </div>

            <div class="submit_button_wrapper">

                <button class="edit_ticket_trigger submit_button">

                    <?php _e( 'Edit Ticket', TEXT_DOMAIN ); ?>

                </button>

            </div>

        </form>

    </div>

</div>
