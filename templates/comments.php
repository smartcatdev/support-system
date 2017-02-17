<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

$comments_enabled = TicketUtils::comments_enabled( $post->ID );

$ticket_status = get_post_meta( $post->ID, 'status', true );
$comments_closed = $ticket_status === 'resolved' || $ticket_status === 'closed';

?>



            <?php if( !empty( $comments ) ) : ?>

                <?php foreach ( $comments as $comment ) : ?>

                    <?php include Plugin::plugin_dir( Plugin::ID ) . '/templates/comment.php'; ?>

                <?php endforeach; ?>

            <?php endif; ?>

      

    <?php if( $comments_enabled ) : ?>

        <div class="comment_reply support_card">

            <div class="status_bar">

                <div class="wrapper">

                    <div class="image_wrapper">

                        <?php echo get_avatar( wp_get_current_user()->ID, 36 ); ?>

                    </div>

                    <div class="meta_wrapper">

                        <p class="author_name"><?php esc_html_e( wp_get_current_user()->display_name ); ?></p>

                    </div>

                </div>

            </div>

            <div class="inner">

                <form class="support_form"
                    data-action="support_submit_comment"
                    data-after="post_comment_submit">

                    <textarea name="content"></textarea>

                    <input type="hidden" name="id" value="<?php echo $post->ID; ?>">

                    <?php wp_comment_form_unfiltered_html_nonce(); ?>

                    <div class="button_wrapper">

                        <button type="submit" class="button submit">

                            <?php _e( get_option( Option::REPLY_BTN_TEXT, Option\Defaults::REPLY_BTN_TEXT ) ); ?>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php else : ?>

        <p class="comments_closed_msg"><?php echo get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ); ?></p>

    <?php endif; ?>


