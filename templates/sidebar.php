<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\utilUtils;

$products = \SmartcatSupport\util\products();
$statuses = \SmartcatSupport\util\statuses();
$status = get_post_meta( $ticket->ID, 'status', true );

$product = get_post_meta( $ticket->ID, 'product', true );
$receipt_id = get_post_meta( $ticket->ID, 'receipt_id', true );

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

                <?php _e( ( array_key_exists( $status, $statuses ) ? $statuses[ $status ] : '—' ), \SmartcatSupport\PLUGIN_ID ); ?>

                <?php if( $status != 'closed' && !current_user_can( 'manage_support_tickets' ) ) : ?>

                    <button class="close-ticket btn btn-default pull-right" data-toggle="modal" data-target="#close-ticket-modal-<?php echo $ticket->ID; ?>">

                        <span class="glyphicon glyphicon-ok-sign button-icon"></span>

                        <span><?php _e( 'Close Ticket', \SmartcatSupport\PLUGIN_ID ); ?></span>

                    </button>

                <?php endif; ?>

            </div>

            <p><?php _e( 'Since ', \SmartcatSupport\PLUGIN_ID ); ?><?php echo \SmartcatSupport\util\just_now( $ticket->post_date ); ?></p>

            <p><?php _e( 'From ' . get_the_date( 'l F j, Y', $ticket ), \SmartcatSupport\PLUGIN_ID ); ?></p>

        </div>

    </div>

    <?php if( \SmartcatSupport\util\ecommerce_enabled() ) : ?>

        <div class="panel panel-default purchase-details" data-id="purchase-details">

            <div class="panel-heading">

                <a href="#collapse-purchase-<?php echo $ticket->ID; ?>" data-toggle="collapse"
                   class="panel-title"><?php _e( 'Purchase Details', \SmartcatSupport\PLUGIN_ID ); ?></a>

            </div>

            <div id="collapse-purchase-<?php echo $ticket->ID; ?>" class="panel-collapse in">

                <div class="panel-body">

                    <div class="product-info">

                        <span class="lead"><?php _e( $product, \SmartcatSupport\PLUGIN_ID ); ?>

                    </div>

                    <?php if( !empty( $receipt_id ) ) : ?>

                        <div class="purchase-info">

                            <span><?php _e( "Receipt # {$receipt_id}", \SmartcatSupport\PLUGIN_ID ); ?></span>

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
                   class="panel-title"><?php _e( 'Customer Details', \SmartcatSupport\PLUGIN_ID ); ?></a>

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

                            <p><?php _e( 'Email: ', \SmartcatSupport\PLUGIN_ID );
                                echo \SmartcatSupport\util\author_email( $ticket ); ?></p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <div class="panel panel-default attachments" data-id="attachments">

        <div class="panel-heading">

            <a href="#collapse-attachments-<?php echo $ticket->ID; ?>" data-toggle="collapse"
               class="panel-title"><?php _e( 'Attachments', \SmartcatSupport\PLUGIN_ID ); ?></a>

        </div>

        <div id="collapse-attachments-<?php echo $ticket->ID; ?>" class="panel-collapse in">

            <div class="panel-body">

                <?php $attachments = \SmartcatSupport\util\get_attachments( $ticket ); ?>
                <?php $attachment_count = count( $attachments ); ?>

                <?php if( $attachment_count === 0 ) : ?>

                    <p class="text-muted"><?php _e( 'There are no attachments for this ticket', \SmartcatSupport\PLUGIN_ID ); ?></p>

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

                                     <?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail', false, 'class=img-responsive attachment-img' ); ?>

                                </div>

                                <div id="caption-<?php echo $attachment->ID; ?>" style="display: none">

                                    <?php $author = get_user_by( 'id', $attachment->post_author ); ?>

                                    <h4><?php echo $author->first_name . ' ' . $author->last_name; ?></h4>
                                    <p><?php echo \SmartcatSupport\util\just_now( $attachment->post_date ); ?></p>

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

                        <span><?php _e( 'Upload', \SmartcatSupport\PLUGIN_ID ); ?></span>

                    </button>

                </div>

            </div>

        </div>

    </div>

    <?php if ( current_user_can( 'manage_support_tickets' ) ) : ?>

        <div class="panel panel-default ticket-properties" data-id="ticket-properties">

            <div class="panel-heading">

                <a href="#collapse-details-<?php echo $ticket->ID; ?>" data-toggle="collapse"
                   class="panel-title"><?php _e( 'Ticket Properties', \SmartcatSupport\PLUGIN_ID ); ?></a>

            </div>

            <div id="collapse-details-<?php echo $ticket->ID; ?>" class="panel-collapse in">

                <div class="message"></div>

                <div class="panel-body">

                    <form class="ticket-status-form">

                        <?php $form = include_once Plugin::plugin_dir( \SmartcatSupport\PLUGIN_ID ) . '/config/ticket_properties_form.php'; ?>

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

                                <span><?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?></span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <?php do_action( 'support_ticket_side_bar', $ticket ); ?>

</div>
