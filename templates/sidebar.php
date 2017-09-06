<?php

use ucare\Options;
use ucare\Plugin;

$products = \ucare\util\products();
$statuses = \ucare\util\statuses();
$status = get_post_meta( $ticket->ID, 'status', true );

$product = get_post_meta( $ticket->ID, 'product', true );
$receipt_id = get_post_meta( $ticket->ID, 'receipt_id', true );

$closed_date = get_post_meta( $ticket->ID, 'closed_date', true );
$closed_by = get_post_meta( $ticket->ID, 'closed_by', true );

if( array_key_exists( $product, $products ) ) {
    $product = $products[$product];
} else {
    $product = 'Not Available';
}

?>

<div class="panel-group">

    <div class="panel panel-default ticket-details" data-id="ticket-details">

        <div class="panel-body">

            <div class="lead">

                <?php _e( ( array_key_exists( $status, $statuses ) ? $statuses[ $status ] : '—' ), 'ucare' ); ?>

                <?php $terms = get_the_terms( $ticket, 'ticket_category' ); ?>

                <?php if( !empty( $terms ) ) : ?>

                    <span class="tag category"><?php echo $terms[0]->name; ?></span>

                <?php endif; ?>

            </div>

            <hr class="sidebar-divider">

            <?php if( empty( $closed_date ) ) : ?>

                <p>
                    <?php _e( 'Since ', 'ucare' ); ?><?php echo \ucare\util\just_now( $ticket->post_modified ); ?>

                    <?php if( get_post_meta( $ticket->ID, 'stale', true ) ) : ?>

                        <span class="glyphicon glyphicon-time ticket-stale"></span>

                    <?php endif; ?>

                </p>

            <?php else : ?>

                <p>

                    <?php if( $closed_by > 0 ) : ?>

                    <?php _e( 'Closed by ', 'ucare' ); ?><?php echo \ucare\util\user_full_name( get_user_by( 'id', $closed_by ) ); ?>

                    <?php else : ?>

                        <?php _e( 'Automatically closed ', \ucare\PLUGIN_ID ); ?>

                    <?php endif; ?>

                    (<?php echo \ucare\util\just_now( $closed_date ); ?>)

                </p>

            <?php endif; ?>

            <p><?php _e( 'From ' . get_the_date( 'l F j, Y @ g:i A', $ticket ), 'ucare' ); ?></p>

        </div>

    </div>

    <?php if( \ucare\util\ecommerce_enabled() ) : ?>

        <div class="panel panel-default purchase-details" data-id="purchase-details">

            <div class="panel-heading">

                <a href="#collapse-purchase-<?php echo $ticket->ID; ?>" data-toggle="collapse"
                   class="panel-title"><?php _e( 'Purchase Details', 'ucare' ); ?></a>

            </div>

            <div id="collapse-purchase-<?php echo $ticket->ID; ?>" class="panel-collapse in">

                <div class="panel-body">

                    <div class="product-info">

                        <span class="lead"><?php _e( $product, 'ucare' ); ?>

                    </div>

                    <?php if( !empty( $receipt_id ) ) : ?>

                        <div class="purchase-info">

                            <span><?php _e( "Receipt # {$receipt_id}", 'ucare' ); ?></span>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <?php if ( current_user_can( 'manage_support_tickets' ) ) : ?>

        <div class="panel panel-default customer-details" data-id="customer-details">

            <div class="panel-heading">

                <a href="#collapse-customer-<?php echo $ticket->ID; ?>" data-toggle="collapse"
                   class="panel-title"><?php _e( 'Customer Details', 'ucare' ); ?></a>

            </div>

            <div id="collapse-customer-<?php echo $ticket->ID; ?>" class="panel-collapse in">

                <div class="panel-body">

                    <div class="media">

                        <div class="media-left">

                            <?php echo get_avatar( $ticket, 48, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

                        </div>

                        <div class="media-body" style="width: auto">

                            <p>

                                <strong class="media-middle"><?php echo get_the_author_meta( 'display_name', $ticket->post_author ); ?></strong>

                            </p>

                            <p><?php _e( 'Email: ', 'ucare' );
                                echo \ucare\util\author_email( $ticket ); ?></p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <div class="panel panel-default attachments" data-id="attachments">

        <div class="panel-heading">

            <a href="#collapse-attachments-<?php echo $ticket->ID; ?>" data-toggle="collapse"
               class="panel-title"><?php _e( 'Attachments', 'ucare' ); ?></a>

        </div>

        <div id="collapse-attachments-<?php echo $ticket->ID; ?>" class="panel-collapse in">

            <div class="panel-body">

                <?php $attachments = \ucare\util\get_attachments( $ticket ); ?>
                <?php $attachment_count = count( $attachments ); ?>

                <?php if( $attachment_count === 0 ) : ?>

                    <p class="text-muted"><?php _e( 'There are no attachments for this ticket', 'ucare' ); ?></p>

                <?php else : ?>

                    <div class="row gallery">

                        <?php foreach ( $attachments as $attachment ) : ?>

                            <div class="image-wrapper col-xs-3 col-sm-12 col-md-4">

                                <?php if( $attachment->post_author == wp_get_current_user()->ID ) : ?>

                                    <span class="glyphicon glyphicon glyphicon-remove delete-attachment"
                                          data-attachment_id="<?php echo $attachment->ID; ?>"
                                          data-ticket_id="<?php echo $ticket->ID; ?>">

                                    </span>

                                <?php endif; ?>

                                <div class="image" data-src="<?php echo wp_get_attachment_url( $attachment->ID ); ?>"
                                     data-sub-html="#caption-<?php echo $attachment->ID; ?>">

                                    <?php if ( strpos( $attachment->post_mime_type, 'image' ) !== false ) : ?>

                                        <?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail', false, 'class=img-responsive attachment-img' ); ?>

                                    <?php else : ?>

                                        <img height="60" src="<?php echo esc_url( Plugin::plugin_url( \ucare\PLUGIN_ID ) . '/assets/images/file-icon.png' ); ?>" />

                                    <?php endif; ?>

                                </div>

                                <div id="caption-<?php echo $attachment->ID; ?>" style="display: none">

                                    <?php $author = get_user_by( 'id', $attachment->post_author ); ?>

                                    <h4><?php echo $author->first_name . ' ' . $author->last_name; ?></h4>
                                    <p><?php echo \ucare\util\just_now( $attachment->post_date ); ?></p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

                <hr class="sidebar-divider">

                <div class="bottom text-right">

                    <button type="submit" class="button button-submit launch-attachment-modal"
                            data-target="#attachment-modal-<?php echo $ticket->ID; ?>"
                            data-toggle="modal">

                        <span class="glyphicon glyphicon-paperclip button-icon"></span>

                        <span><?php _e( 'Upload', 'ucare' ); ?></span>

                    </button>

                </div>

            </div>

        </div>

    </div>

    <?php if ( current_user_can( 'manage_support_tickets' ) ) : ?>

        <div class="panel panel-default ticket-properties" data-id="ticket-properties">

            <div class="panel-heading">

                <a href="#collapse-details-<?php echo $ticket->ID; ?>" data-toggle="collapse"
                   class="panel-title"><?php _e( 'Ticket Properties', 'ucare' ); ?></a>

            </div>

            <div id="collapse-details-<?php echo $ticket->ID; ?>" class="panel-collapse in">

                <div class="message"></div>

                <div class="panel-body">

                    <form class="ticket-status-form" method="post">

                        <?php $form = include_once Plugin::plugin_dir( \ucare\PLUGIN_ID ) . '/config/ticket_properties_form.php'; ?>

                        <?php foreach ( $form->fields as $field ) : ?>

                            <div class="form-group">

                                <label><?php echo $field->label; ?></label>

                                <?php $field->render(); ?>

                            </div>

                        <?php endforeach; ?>

                        <input type="hidden" name="id" value="<?php echo $ticket->ID; ?>"/>
                        <input type="hidden" name="<?php echo $form->id; ?>"/>

                        <hr class="sidebar-divider">

                        <div class="bottom text-right">

                            <button type="submit" class="button button-submit">

                                <span class="glyphicon glyphicon-floppy-save button-icon"></span>

                                <span><?php _e( get_option( Options::SAVE_BTN_TEXT, \ucare\Defaults::SAVE_BTN_TEXT ) ); ?></span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <?php do_action( 'support_ticket_side_bar', $ticket ); ?>

</div>
