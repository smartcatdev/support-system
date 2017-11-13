<?php

use ucare\Options;

$attachments = get_attached_media( 'image', $ticket->ID );
$attachment_count = count( $attachments );

$status = get_post_meta( $ticket->ID, 'status', true );

$user = wp_get_current_user();

?>
<div class="loader-mask"></div>

<div class="row ticket-detail" style="display: none">

    <div class="sidebar col-sm-4 col-sm-push-8"><p class="text-center"><?php _e( 'Loading...', 'ucare' ); ?></p></div>

    <div class="discussion-area col-sm-8 col-sm-pull-4">

        <div class="ticket panel panel-default">

            <div class="panel-heading">

                <p class="panel-title"><?php esc_html_e( $ticket->post_title ); ?></p>

            </div>

            <div class="panel-body">

                <p class="formatted"><?php echo $ticket->post_content; ?></p>

                <?php do_action( 'ucare_after_ticket_content', $ticket ); ?>

            </div>

        </div>

        <div class="comments"></div>

        <div class="comment-reply-wrapper">

            <div class="comment comment-reply panel panel-default">

                <div class="panel-heading nav-header">

                  <ul class="nav nav-tabs">

                      <li class="tab editor-tab active edit">
                          <a class="nav-link" data-toggle="tab" href="#ticket-<?php echo $ticket->ID; ?>-editor"><?php _e( 'Write', 'ucare' ); ?></a>
                      </li>

                      <li class="tab editor-tab preview">
                          <a class="nav-link" data-toggle="tab" href="#ticket-<?php echo $ticket->ID; ?>-preview"><?php _e( 'Preview', 'ucare' ); ?></a>
                      </li>

                  </ul>

                </div>

                <div class="panel-body editor-area">

                    <div class="editor">

                        <form class="comment-form">

                        <div class="tab-content">

                            <div id="ticket-<?php echo $ticket->ID; ?>-editor" class="editor-pane tab-pane active">

                                <textarea class="editor-content form-control" name="content" rows="5"></textarea>

                            </div>

                            <div id="ticket-<?php echo $ticket->ID; ?>-preview" class="tab-pane preview">

                                <div class="rendered formatted"></div>

                            </div>

                        </div>

                        <input type="hidden" name="id" value="<?php echo $ticket->ID; ?>">

                        <div class="bottom">

                            <span class="text-right">

                                <?php if( $status != 'closed' && !current_user_can( 'manage_support_tickets' ) ) : ?>

                                    <button class="close-ticket button"
                                            type="button"
                                            data-ticket_id="<?php echo $ticket->ID; ?>">

                                        <span class="glyphicon glyphicon-ok-sign button-icon"></span>

                                        <span><?php _e( 'Close Ticket', 'ucare' ); ?></span>

                                    </button>

                                <?php endif; ?>

                                    <button type="submit" class="button button-submit" disabled="true">

                                        <span class="glyphicon glyphicon-send button-icon"></span>

                                        <span><?php _e( get_option( Options::REPLY_BTN_TEXT, \ucare\Defaults::REPLY_BTN_TEXT ) ); ?></span>

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

<div id="attachment-modal-<?php echo $ticket->ID; ?>"
     data-ticket_id="<?php echo $ticket->ID; ?>"
     class="modal attachment-modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"><?php _e( 'Attach Images', 'ucare' ); ?></h4>

            </div>

            <div class="modal-body">

                <form id="attachment-dropzone-<?php echo $ticket->ID; ?>" class="dropzone">

                    <?php wp_nonce_field( 'support_ajax', '_ajax_nonce' ); ?>

                    <input type="hidden" name="ticket_id" value="<?php echo $ticket->ID; ?>" />

                </form>

            </div>

            <div class="modal-footer">

                <button type="button" class="button button-submit close-modal"
                        data-target="#attachment-modal-<?php echo $ticket->ID; ?>"
                        data-toggle="modal">

                    <?php _e( 'Done', 'ucare' ); ?>

                </button>

            </div>

        </div>

    </div>

</div>
