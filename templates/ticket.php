<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket_detail">

    <div class="ticket_editor">

        <form class="edit_ticket_form" data-action="<?php esc_attr_e( $ticket_action ); ?>">

            <?php Form::form_fields( $editor_form ); ?>

            <div class="text_right hidden">

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

        </form>

        <div class="text_right">

            <button class="edit_ticket_trigger submit_button">

                <?php _e( 'Edit Ticket', TEXT_DOMAIN ); ?>

            </button>

        </div>

    </div>

    <?php if( isset( $comments ) ) : ?>

        <div class="comment_section">

            <form class="comment_form" data-action="<?php esc_attr_e( $comment_action ); ?>">

                <?php Form::form_fields( $comment_form ); ?>

                <div class="text_right">

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

            <div class="comments">

                <?php if( !empty( $comments ) ) : ?>

                    <?php foreach ( $comments as $comment ) : ?>

                        <?php include 'comment.php'; ?>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </div>

    <?php endif; ?>

</div>
