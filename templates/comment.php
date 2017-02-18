<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>

<div id="comment-<?php echo $comment->comment_ID; ?>"
     data-id="<?php esc_attr_e( $comment->comment_ID ); ?>"
     class="comment panel panel-default">

    <div class="panel-heading">

        <div class="media pull-left meta">

            <div class="media-left">

                <?php echo get_avatar( $comment, 28, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

            </div>

            <div class="media-body" style="width: auto">

                <p class="media-heading"><?php echo $comment->comment_author; ?></p>

                <p class="text-muted"><?php _e( human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) . ' ago', Plugin::ID ); ?></p>

            </div>

        </div>

        <div class="pull-right">

            <div class="btn-group">

                <?php if ( $comment->user_id == wp_get_current_user()->ID && current_user_can( 'edit_comments' ) && $comments_enabled ) : ?>

                    <button class="btn btn-default icon-bin delete-comment"
                            data-id="<?php echo $comment->comment_ID; ?>"></button>

                    <button class="btn btn-default icon-pencil edit-comment"></button>

                <?php endif; ?>

            </div>

        </div>

        <div class="clearfix"></div>

    </div>

    <div class="panel-body">

        <div class="comment-content"><?php echo $comment->comment_content; ?></div>

        <?php if( $comments_enabled ) : ?>

            <div class="editor">

                <form class="edit-comment-form">

                    <textarea class="editor-content" name="content" rows="5"></textarea>

                    <input class="comment-id" type="hidden" name="comment_id" value="<?php echo $comment->comment_ID; ?>">

                    <?php wp_comment_form_unfiltered_html_nonce(); ?>

                    <div class="row">

                        <div class="bottom col-sm-12">

                            <button type="button" class="button cancel-edit-comment">

                                <?php _e( get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ) ); ?>

                            </button>

                            <button type="submit" class="button save-comment button-submit">

                                <?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?>

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        <?php endif; ?>

    </div>

</div>

