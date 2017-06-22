<?php

use ucare\Options;

?>

<div class="wrapper">

    <div id="comment-<?php echo $comment->comment_ID; ?>"
         data-id="<?php esc_attr_e( $comment->comment_ID ); ?>"
         class="comment panel panel-default <?php echo $comment->user_id == wp_get_current_user()->ID ? 'current-user' : null;?>">

        <div class="panel-heading nav-header">

            <div class="media pull-left meta">

                <div class="media-left">

                    <?php echo get_avatar( $comment, 28, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

                </div>

                <div class="media-body" style="width: auto">

                    <p class="media-heading"><?php echo $comment->comment_author; ?></p>

                    <p class="text-muted"><?php echo \ucare\util\just_now( $comment->comment_date ); ?></p>

                </div>

            </div>

            <?php if ( $comment->user_id == wp_get_current_user()->ID && current_user_can( 'edit_support_ticket_comments' ) ) : ?>

                <ul class="nav nav-tabs" style="display: none">

                    <li class="tab editor-tab active">
                        <a class="nav-link edit" data-toggle="tab" href="#comment-<?php echo $comment->comment_ID; ?>-editor"><?php _e( 'Write', 'ucare' ); ?></a>
                    </li>

                    <li class="tab editor-tab preview">
                        <a class="nav-link" data-toggle="tab" href="#comment-<?php echo $comment->comment_ID; ?>-preview"><?php _e( 'Preview', 'ucare' ); ?></a>
                    </li>

                </ul>

                <div class="pull-right">

                    <div class="btn-group comment-controls">

                        <button class="btn btn-default glyphicon glyphicon-trash delete-comment"
                                data-id="<?php echo $comment->comment_ID; ?>"></button>

                        <button class="btn btn-default glyphicon glyphicon-pencil edit-comment"></button>

                    </div>

                </div>

            <?php endif; ?>

            <div class="clearfix"></div>

        </div>

        <div class="panel-body editor-area">

            <div class="comment-content formatted"><?php echo stripcslashes( $comment->comment_content ); ?></div>

            <div class="editor">

                <form class="edit-comment-form">

                    <div class="tab-content">

                        <div id="comment-<?php echo $comment->comment_ID; ?>-editor" class="editor-pane tab-pane active">

                            <textarea class="editor-content form-control" name="content" rows="5"></textarea>

                        </div>

                        <div id="comment-<?php echo $comment->comment_ID; ?>-preview" class="tab-pane preview">

                            <div class="rendered formatted"></div>

                        </div>

                    </div>

                    <input class="comment-id" type="hidden" name="comment_id" value="<?php echo $comment->comment_ID; ?>">

                    <div class="bottom text-right">

                        <button type="button" class="button cancel-edit-comment">

                            <span class="glyphicon glyphicon-remove-sign button-icon"></span>

                            <span><?php _e( get_option( Options::CANCEL_BTN_TEXT, \ucare\Defaults::CANCEL_BTN_TEXT ) ); ?></span>

                        </button>

                        <button type="submit" class="button save-comment button-submit">

                            <span class="glyphicon glyphicon-floppy-save button-icon"></span>

                            <span><?php _e( get_option( Options::SAVE_BTN_TEXT, \ucare\Defaults::SAVE_BTN_TEXT ) ); ?></span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
