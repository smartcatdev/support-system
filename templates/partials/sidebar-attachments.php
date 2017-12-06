<?php
/**
 * Template for attachments section of ticket sidebar.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;

?>


<!-- panel-body -->

    <?php if ( count( $files ) > 0 ) : ?>

        <div class="row">

            <?php foreach ( $files as $file ) : ?>

                <div class="col-md-4">

                    <div class="file-wrapper">

                        <?php if ( $file->post_author == wp_get_current_user()->ID ) : ?>

                            <span class="glyphicon glyphicon glyphicon-remove delete-attachment"
                                  data-attachment_id="<?php esc_html_e( $file->ID ); ?>"
                                  data-ticket_id="<?php esc_html_e( $ticket->ID ); ?>"></span>

                        <?php endif; ?>

                        <a target="_blank" href="<?php echo esc_url( wp_get_attachment_url( $file->ID ) ); ?>">

                            <div class="file">

                                <div class="icon">

                                    <img src="<?php echo esc_url( resolve_url( '/assets/images/document.png' ) ); ?>" />

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

    <?php if ( count( $images ) > 0 ) : $ctr = 0; ?>

        <div class="row">

            <div class="gallery">

                <?php foreach ( $images as $image ) : $ctr++; ?>
                    
                    <div class="col-md-4">

                        <div class="image-wrapper">

                            <?php if( $image->post_author == wp_get_current_user()->ID ) : ?>

                                <span class="glyphicon glyphicon glyphicon-remove delete-attachment"
                                      data-attachment_id="<?php esc_attr_e( $image->ID ); ?>"
                                      data-ticket_id="<?php esc_attr_e( $ticket->ID ); ?>"></span>

                            <?php endif; ?>

                            <div class="image"
                                 data-src="<?php echo esc_url( wp_get_attachment_url( $image->ID ) ); ?>"
                                 data-sub-html="#caption-<?php esc_attr_e( $image->ID ); ?>"
                                 style="background-image: url( <?php echo esc_url( wp_get_attachment_url( $image->ID ) ); ?> )"></div>

                            <div id="caption-<?php esc_attr_e( $image->ID ); ?>" style="display: none">

                                <h4><?php esc_html_e( get_user_field( 'display_name', $image->post_author ) ); ?></h4>
                                <p><?php time_diff( $image->post_date_gmt ); ?></p>

                            </div>

                        </div>
                        
                    </div>
                
                    <?php if( $ctr == 3 ) : ?>
                    <div class="clear"></div>
                    <?php endif; ?>
                
                    <?php endforeach; ?>

                </div>

            </div>

            <hr class="sidebar-divider">

        </div>

    <?php endif; ?>

    <div class="bottom text-right">

        <button type="submit"
                class="button button-submit launch-attachment-modal"
                data-target="#attachment-modal-<?php esc_attr_e( $ticket->ID ); ?>"
                data-toggle="modal">

            <span class="glyphicon glyphicon-paperclip button-icon"></span>
            <span><?php _e( 'Upload', 'ucare' ); ?></span>

        </button>

    </div>

<!-- /panel-body -->