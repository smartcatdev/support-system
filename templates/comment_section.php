<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="comment_section">

    <div class="comments">

        <?php if ( !empty( $comments ) ) : ?>

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
