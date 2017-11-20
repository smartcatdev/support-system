<?php
/**
 * Template for attachments section of ticket sidebar.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>




                <?php $files = \ucare\util\get_attachments( $ticket, 'post_date', 'DESC', \ucare\allowed_mime_types( 'file' ) ); ?>

                <?php if ( count( $files ) > 0 ) : ?>

                    <div class="row">

                        <?php foreach ( $files as $file ) : ?>

                            <div class="col-md-4">

                                <div class="file-wrapper">

                                    <?php if( $file->post_author == wp_get_current_user()->ID ) : ?>

                                        <span class="glyphicon glyphicon glyphicon-remove delete-attachment"
                                              data-attachment_id="<?php echo $file->ID; ?>"
                                              data-ticket_id="<?php echo $ticket->ID; ?>">

                                        </span>

                                    <?php endif; ?>

                                    <a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( $file->ID ) ); ?>">

                                        <div class="file">

                                            <div class="icon">

                                                <img src="<?php echo esc_url( \ucare\plugin_url( '/assets/images/document.png' ) ); ?>" />

                                            </div>

                                            <div class="filename">

                                                <div><?php esc_html_e( mb_strimwidth( $file->post_title, 0, 50, '...' ) ); ?></div>

                                            </div>

                                        </div>

                                    </a>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <hr class="sidebar-divider">

                <?php endif; ?>

                <?php $images = \ucare\util\get_attachments( $ticket, 'post_date', 'DESC', \ucare\allowed_mime_types( 'image' ) ); ?>

                <?php if ( count( $images ) > 0 ) : ?>

                    <div class="row">

                        <div class="gallery">

                            <?php foreach ( $images as $image ) : ?>

                                <div class="col-md-4">

                                    <div class="image-wrapper">

                                        <?php if( $image->post_author == wp_get_current_user()->ID ) : ?>

                                            <span class="glyphicon glyphicon glyphicon-remove delete-attachment"
                                                  data-attachment_id="<?php echo $image->ID; ?>"
                                                  data-ticket_id="<?php echo $ticket->ID; ?>">

                                            </span>

                                        <?php endif; ?>

                                        <div class="image" data-src="<?php echo wp_get_attachment_url( $image->ID ); ?>"
                                             data-sub-html="#caption-<?php echo $image->ID; ?>"
                                             style="background-image: url( <?php echo wp_get_attachment_url( $image->ID ); ?> )"></div>

                                        <div id="caption-<?php echo $image->ID; ?>" style="display: none">

                                                <?php $author = get_user_by( 'id', $image->post_author ); ?>

                                                <h4><?php echo $author->first_name . ' ' . $author->last_name; ?></h4>
                                                <p><?php echo \ucare\util\just_now( $image->post_date ); ?></p>

                                            </div>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                        <hr class="sidebar-divider">

                    <?php endif; ?>

                    <div class="bottom text-right">

                        <button type="submit" class="button button-submit launch-attachment-modal"
                                data-target="#attachment-modal-<?php echo $ticket->ID; ?>"
                                data-toggle="modal">

                            <span class="glyphicon glyphicon-paperclip button-icon"></span>

                            <span><?php _e( 'Upload', 'ucare' ); ?></span>

                        </button>

                    </div>

