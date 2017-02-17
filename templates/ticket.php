<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

$comments_enabled = TicketUtils::comments_enabled( $ticket->ID );

?>

<div class="row">

    <div class="sidebar col-sm-4"></div>

    <div class="ticket support_card col-sm-8" data-id="<?php esc_attr_e( $ticket->ID ); ?>">

        <div class="status_bar">

            <div class="image_wrapper">
                
                <?php echo get_avatar( $ticket->post_author, 48 ); ?>

            </div>

        </div>

        <div class="inner">

            <h2 class="subject"><?php esc_html_e( $ticket->post_title ); ?></h2>

            <div class="content"><?php echo $ticket->post_content; ?></div>

        </div>

        <div class="date_opened">

            <?php _e( 'Opened ', Plugin::ID ); echo get_the_date( 'l F n, Y', $ticket ); ?>

        </div>

    </div>

    <div class="comments col-sm-8 pull-right"></div>

    <?php if( $comments_enabled ) : ?>

        <div class="comment-reply support_card col-sm-8 pull-right" style="display: none;">

            <div class="status_bar">

                <div class="wrapper">

                    <div class="image_wrapper">

                        <?php echo get_avatar( wp_get_current_user()->ID, 36 ); ?>

                    </div>

                    <div class="meta_wrapper">

                        <p class="author_name"><?php esc_html_e( wp_get_current_user()->display_name ); ?></p>

                    </div>

                </div>

            </div>

            <div class="inner">

                <form class="comment-form">

                    <textarea class="editor-content" name="content"></textarea>

                    <input type="hidden" name="id" value="<?php echo $ticket->ID; ?>">

                    <?php wp_comment_form_unfiltered_html_nonce(); ?>

                    <div class="bottom">

                        <button type="submit" class="button button-primary button-submit" disabled="true">

                            <?php _e( get_option( Option::REPLY_BTN_TEXT, Option\Defaults::REPLY_BTN_TEXT ) ); ?>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php else : ?>

        <p class="comments_closed_msg"><?php echo get_option( Option::COMMENTS_CLOSED_MSG, Option\Defaults::COMMENTS_CLOSED_MSG ); ?></p>

    <?php endif; ?>



</div>
