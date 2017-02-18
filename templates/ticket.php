<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

$comments_enabled = TicketUtils::comments_enabled( $ticket->ID );

?>

<div class="row">

    <div class="sidebar col-sm-4"></div>

    <div class="col-sm-8">

        <div class="ticket panel panel-default ">

            <div class="panel-heading">

                <p class="panel-title"><?php esc_html_e( $ticket->post_title ); ?></p>

            </div>

            <div class="panel-body">

                <p><?php echo $ticket->post_content; ?></p>

            </div>

        </div>

    </div>

    <div class="comments col-sm-8"></div>

    <?php if( $comments_enabled ) : ?>

        <div class="col-sm-8">

            <div class="comment-reply panel panel-default ">

                <div class="panel-heading">

                    <div class="media meta">

                        <div class="media-left">

                            <?php echo get_avatar( $comment, 28, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

                        </div>

                        <div class="media-body" style="width: auto">

                            <p class="media-heading"><?php echo $comment->comment_author; ?></p>

                        </div>

                    </div>

                </div>

                <div class="panel-body">

                    <div class="editor">

                        <form class="comment-form">

                            <textarea class="editor-content" name="content" rows="5"></textarea>

                            <input class="comment-id" type="hidden" name="id" value="<?php echo $ticket->ID; ?>">

                            <?php wp_comment_form_unfiltered_html_nonce(); ?>

                            <div class="row">

                                <div class="bottom col-sm-12">

                                    <button type="submit" class="button button-submit" disabled="true">

                                        <?php _e( get_option( Option::REPLY_BTN_TEXT, Option\Defaults::REPLY_BTN_TEXT ) ); ?>

                                    </button>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    <?php else : ?>

        <p class="comments_closed_msg"><?php echo get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ); ?></p>

    <?php endif; ?>

</div>
