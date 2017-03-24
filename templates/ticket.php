<?php

use SmartcatSupport\descriptor\Option;

$attachments = get_attached_media( 'image', $ticket->ID );
$attachment_count = count( $attachments );

$user = wp_get_current_user();

?>
<div class="loader-mask"></div>

<div class="row ticket-detail" style="display: none">

    <div class="sidebar col-sm-4 col-sm-push-8"><p class="text-center"><?php _e( 'Loading...', \SmartcatSupport\PLUGIN_ID ); ?></p></div>

    <div class="discussion-area col-sm-8 col-sm-pull-4">

        <div class="ticket panel panel-default ">

            <div class="panel-heading">

                <p class="panel-title"><?php esc_html_e( $ticket->post_title ); ?></p>

            </div>

            <div class="panel-body">

                <p><?php echo $ticket->post_content; ?></p>

                <?php if( $attachment_count > 0 ) : ?>

                    <hr class="attachment-divider">

                    <p class="text-muted attachment-count">

                        <?php echo $attachment_count . _n( ' Attachment', ' Attachments', $attachment_count, \SmartcatSupport\PLUGIN_ID ); ?>

                    </p>

                    <div class="attachments">

                        <?php foreach( $attachments as $attachment ) : ?>

                            <a href="<?php echo wp_get_attachment_url( $attachment->ID ); ?>" data-lightbox="<?php echo $ticket->ID; ?>">

                                <?php echo wp_get_attachment_image( $attachment->ID ); ?>

                            </a>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

        <div class="comments"></div>

        <div class="comment-reply-wrapper">

            <div class="comment comment-reply panel panel-default">

                <div class="panel-heading">

                    <div class="media pull-left meta">

                        <div class="media-left">

                            <?php echo get_avatar( $user->ID, 28, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

                        </div>

                        <div class="media-body" style="width: auto">

                            <p class="media-heading"><?php echo $user->first_name . ' ' . $user->last_name; ?></p>

                        </div>

                    </div>

                    <div class="pull-right">

                        <div class="btn-group comment-controls">

                            <button class="btn btn-default glyphicon glyphicon-paperclip"></button>

                        </div>

                    </div>

                    <div class="clearfix"></div>

                </div>

                <div class="panel-body">

                    <div class="editor">

                        <form class="comment-form">

                            <textarea class="editor-content" name="content" rows="5"></textarea>

                            <input type="hidden" name="id" value="<?php echo $ticket->ID; ?>">

                            <div class="bottom">

                                <span class="text-right">

                                    <button type="submit" class="button button-submit" disabled="true">

                                        <?php _e( get_option( Option::REPLY_BTN_TEXT, Option\Defaults::REPLY_BTN_TEXT ) ); ?>

                                    </button>

                                </span>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
