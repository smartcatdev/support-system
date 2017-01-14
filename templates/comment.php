<?php

use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;

?>

<div class="comment support_card" data-id="<?php esc_attr_e( $comment->comment_ID ); ?>">

    <div class="status_bar">

        <div class="image_wrapper">

            <?php echo get_avatar( $comment, 36 ); ?>

        </div>

        <div class="meta_wrapper">

            <p class="author_name"><?php esc_html_e( $comment->comment_author ); ?></p>

            <p class="date_posted">

                <?php _e( human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) . ' ago', PLUGIN_ID ); ?>

            </p>

        </div>

        <div class="actions">

            <?php if ( $comment->user_id == wp_get_current_user()->ID && current_user_can( 'edit_comments' ) ) : ?>

                <i class="trigger icon-bin" data-action="delete_comment"></i>
                <i class="trigger icon-pencil" data-action="edit_comment"></i>

            <?php endif; ?>

        </div>

    </div>

    <div class="inner">

        <div class="content"><?php echo $comment->comment_content; ?></div>

        <div class="editor hidden">

            <form class="support_form"
                  data-action="support_update_comment"
                  data-after="post_comment_update">

                <textarea name="content"><?php echo $comment->comment_content; ?></textarea>

                <input type="hidden" name="comment_id" value="<?php echo $comment->comment_ID; ?>">

                <?php wp_comment_form_unfiltered_html_nonce(); ?>

                <div class="button_wrapper">

                    <button class="trigger button cancel" data-action="cancel_comment_edit">

                        <?php _e( get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ) ); ?>

                    </button>

                    <button type="input" class="button submit">

                        <?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?>

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>


