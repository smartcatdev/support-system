<?php

use SmartcatSupport\descriptor\Option;

?>

<div class="comment_section">

    <div class="comments">

        <?php if ( !empty( $comments ) ) : ?>

            <?php foreach ( $comments as $comment ) : ?>

                <?php include 'comment.php'; ?>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

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

</div>
