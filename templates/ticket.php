<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket_detail">

    <div class="ticket_editor">


        <form class="edit_ticket_form" data-action="save_support_ticket">

            <?php Form::form_fields( $form ); ?>

            <input type="hidden" class="hidden" name="ticket_id" value="<?php esc_attr_e( isset( $post ) ? $post->ID : '' ); ?>" />

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
            <button class="edit_ticket_trigger submit_button"><?php _e( 'Edit Ticket', TEXT_DOMAIN ); ?></button>
        </div>

    </div>

    <?php if( isset( $post ) ) : ?>

        <?php if( !$comments ) : ?>

            <div>
                <?php _e( 'There are no replies for this ticket yet ', TEXT_DOMAIN ); ?>
                <a href="#" class="reply_trigger"><?php _e( 'click to reply', TEXT_DOMAIN ); ?></a>
            </div>

        <?php endif; ?>

        <div class="comment_section hidden">

            <form class="comment_form" data-action="save_support_comment">

                <textarea class="form_field" name="comment_content"></textarea>
                <input type="hidden" class="hidden" name="ticket_id" value="<?php esc_attr_e( $post->ID ); ?>" />

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

            <?php if( $comments ) : ?>

                <?php foreach ( $comments as $comment ) : ?>

                    <div class="comment">

                        <p class="content"><?php esc_html_e( $comment->comment_content ); ?></p>

                        <p class="comment_details">

                            <span class="author"><?php esc_html_e( $comment->comment_author ); ?></span>
                            <span class="date_posted"><?php esc_html_e( $comment->comment_date ); ?></span>

                        </p>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>
