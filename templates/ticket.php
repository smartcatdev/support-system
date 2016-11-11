<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket_detail">

    <div class="ticket_editor">

        <form class="edit_ticket_form" data-action="<?php esc_attr_e( $ticket_action ); ?>">

            <?php Form::form_fields( $editor_form ); ?>


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

    <?php if( isset( $comments ) ) : ?>

        <div class="comment_section">

            <div class="comments">

                <?php if( !empty( $comments ) ) : ?>

                    <?php foreach ( $comments as $comment ) : ?>

                        <?php include 'comment.php'; ?>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <form class="comment_form" data-action="<?php esc_attr_e( $comment_action ); ?>">

                <?php Form::form_fields( $comment_form ); ?>

                <?php wp_comment_form_unfiltered_html_nonce(); ?>

                <div class="submit_button_wrapper">

                    <button class="submit_button">

                        <div class="status hidden"></div>
                        <span class="text"
                              data-default="<?php _e( 'Reply', TEXT_DOMAIN ); ?>"
                              data-success="<?php _e( 'Sent', TEXT_DOMAIN ); ?>"
                              data-fail="<?php _e( 'Error', TEXT_DOMAIN ); ?>"
                              data-wait="<?php _e( 'Sending', TEXT_DOMAIN ); ?>">

                                <?php _e( 'Reply', TEXT_DOMAIN ); ?>

                        </span>

                    </button>

                </div>

            </form>

        </div>

    <?php endif; ?>

</div>